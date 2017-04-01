<?php

namespace TyHand\JsonApiToolsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use TyHand\JsonApiToolsBundle\Util\Inflect;
use TyHand\JsonApiToolsBundle\ApiResource\IncludeManager;
use TyHand\JsonApiToolsBundle\ApiResource\LinkGenerator;

class ResourceController extends Controller
{
    /**
     * Index Action
     *
     * Outputs a list of the resource and accepts filter and sort parameters
     */
    public function resourceIndexAction(Request $request)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $result = $resource->find($request->query);
        $includeManager = $this->createIncludesManager($request);
        $linkGenerator = $this->createLinkGenerator($request);
        $json = ['data' => []];
        foreach($result->getResults() as $entity) {
            $json['data'][] = $resource->toJson($entity, $includeManager);
        }

        if ($includeManager->hasData()) {
            $json['included'] = $includeManager->toJson();
        }

        $json['links'] = $linkGenerator->generatePaginationLinks($result);
        $json['meta'] = $result->generateMetaJson();

        return new JsonResponse($this->postProcessJson($request, $json));
    }

    /**
     * Create Action
     *
     * Creates a new instance of the resource and persists it to the database
     */
    public function resourceCreateAction(Request $request)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $entity = $resource->toEntity(json_decode($request->getContent(), true)['data']);

        if ($resource->getUseVoters()) {
            $this->denyAccessUnlessGranted($resource->getVoterCreateAttribute(), $entity);
        }

        $errors = $resource->validate($entity, $this->get('validator'));
        if (0 < count($errors)) {
            return $this->createErrorResponse($errors);
        }

        $entity = $resource->postCreate($entity);

        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();

        $json = ['data' => $resource->toJson($entity)];

        return new JsonResponse($this->postProcessJson($request, $json));
    }

    /**
     * Show Action
     *
     * Returns the specified resource
     */
    public function resourceShowAction(Request $request, $id)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $entity = $this->getDoctrine()->getManager()->getRepository($resource->getEntity())->findOneById($id);

        if ($resource->getUseVoters()) {
            $this->denyAccessUnlessGranted($resource->getVoterViewAttribute(), $entity);
        }

        $includeManager = $this->createIncludesManager($request);
        $json = ['data' => $resource->toJson($entity, $includeManager)];

        if ($includeManager->hasData()) {
            $json['included'] = $includeManager->toJson();
        }

        return new JsonResponse($this->postProcessJson($request, $json));
    }

    /**
     * Edit Action
     *
     * Makes changes to the specified resource
     */
    public function resourceEditAction(Request $request, $id)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $entity = $resource->toEntity(json_decode($request->getContent(), true)['data']);

        if ($resource->getUseVoters()) {
            $this->denyAccessUnlessGranted($resource->getVoterEditAttribute(), $entity);
        }

        $errors = $resource->validate($entity, $this->get('validator'));
        if (0 < count($errors)) {
            return $this->createErrorResponse($errors);
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($this->postProcessJson($request, ['data' => $resource->toJson($entity)]));
    }

    /**
     * Delete Action
     *
     * Deletes the resource.  Note, make sure database stuff is ready to handle this
     */
    public function resourceDeleteAction(Request $request, $id)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $entity = $resource->loadEntityById($id);

        if (!$resource->getAllowDelete()) {
            throw $this->createAccessDeniedException('This operation is not allowed');
        }

        if ($resource->getUseVoters()) {
            $this->denyAccessUnlessGranted($resource->getVoterDeleteAttribute(), $entity);
        }

        $this->getDoctrine()->getManager()->remove($entity);
        $this->getDoctrine()->getManager()->flush();

        return new Response('', 204);
    }

    /**
     * Show Relationship Action
     *
     * Returns a list of Resource Identifier Objects for the requested instance and relationship
     */
    public function resourceShowRelationshipsAction(Request $request, $id, $relationship)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $entity = $resource->loadEntityById($id);
        if (!$entity) {
            throw new \Exception('Entity not found');
        }
        $relation = $resource->getRelationshipByJsonName($relationship);
        if (!$relation) {
            throw new \Exception('Relationship not found');
        }

        if ($resource->getUseVoters()) {
            $this->denyAccessUnlessGranted($resource->getVoterViewAttribute(), $entity);
        }

        $data = $relation->getResourceIdentifierJson($entity);

        $json = ['data' => $data];

        return new JsonResponse($this->postProcessJson($request, $json));
    }

    /**
     * Show Full Relationship Action
     *
     * Returns a complete list of the resources in the relationship
     */
    public function resourceShowRelationshipsFullAction(Request $request, $id, $relationship)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $entity = $resource->loadEntityById($id);
        if (!$entity) {
            throw new \Exception('Entity not found');
        }
        $relation = $resource->getRelationshipByJsonName($relationship);
        if (!$relation) {
            throw new \Exception('Relationship not found');
        }

        if ($resource->getUseVoters()) {
            $this->denyAccessUnlessGranted($resource->getVoterViewAttribute(), $entity);
        }

        $object = $relation->getRelatedFromEntity($entity);
        $relatedResource = $this->get('jsonapi_tools.resource_manager')->getResource($relation->getResource());
        $includeManager = $this->createIncludesManager($request);

        if (is_array($object) || $object instanceof \Doctrine\Common\Collections\Collection) {
            $json = ['data' => []];
            foreach($object as $part) {
                $json['data'][] = $relatedResource->toJson($part, $includeManager);
            }
        } else {
            $json = ['data' => $relatedResource->toJson($object, $includeManager)];
        }

        if ($includeManager->hasData()) {
            $json['included'] = $includeManager->toJson();
        }

        return new JsonResponse($this->postProcessJson($request, $json));
    }

    /**
     * Edit Relationships Action
     *
     * Make changes to the relationship (Note, the relationship not the resource in the relationship)
     * This will replace the current relationship with the new one, which in the case of a has Many
     * will be a complete replacement.
     */
    public function resourceEditRelationshipsAction(Request $request, $id, $relationship)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $entity = $resource->loadEntityById($id);
        if (!$entity) {
            throw new \Exception('Entity not found');
        }
        $relation = $resource->getRelationshipByJsonName($relationship);
        if (!$relation) {
            throw new \Exception('Relationship not found');
        }

        if ($resource->getUseVoters()) {
            $this->denyAccessUnlessGranted($resource->getVoterEditAttribute(), $entity);
        }

        $entity = $relation->addToEntity($entity, json_decode($request->getContent(), true), $this->get('jsonapi_tools.resource_manager'));

        $errors = $resource->validate($entity, $this->get('validator'));
        if (0 < count($errors)) {
            return $this->createErrorResponse($errors);
        }

        $this->getDoctrine()->getManager()->flush();

        $json = ['data' => $relation->getResourceIdentifierJson($entity)];

        return new JsonResponse($this->postProcessJson($request, $json));
    }

    /**
     * Add Relationship Action
     *
     * Adds a new resource to a has many relationship, and won't replace any existing relationships
     */
    public function resourceAddRelationshipsAction(Request $request, $id, $relationship)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $entity = $resource->loadEntityById($id);
        if (!$entity) {
            throw new \Exception('Entity not found');
        }
        $relation = $resource->getRelationshipByJsonName($relationship);
        if (!$relation) {
            throw new \Exception('Relationship not found');
        } elseif (!($relation instanceof \TyHand\JsonApiToolsBundle\ApiResource\HasManyRelationship)) {
            throw new \Exception('Method is only for Has Many Relationships');
        }

        if ($resource->getUseVoters()) {
            $this->denyAccessUnlessGranted($resource->getVoterEditAttribute(), $entity);
        }

        $relation->setModeToAdd();
        $entity = $relation->addToEntity($entity, json_decode($request->getContent(), true), $this->get('jsonapi_tools.resource_manager'));

        $errors = $resource->validate($entity, $this->get('validator'));
        if (0 < count($errors)) {
            return $this->createErrorResponse($errors);
        }

        $this->getDoctrine()->getManager()->flush();

        $json = ['data' => $relation->getResourceIdentifierJson($entity)];

        return new JsonResponse($this->postProcessJson($request, $json));
    }

    /**
     * Remove Relationship Action
     *
     * Removes a resource from a has many relationship.  Again, this is just the relationship, the resource will remain.
     */
    public function resourceRemoveRelationshipsAction(Request $request, $id, $relationship)
    {
        $resource = $this->get('jsonapi_tools.resource_manager')->getResource($this->getResourceName());
        $entity = $resource->loadEntityById($id);
        if (!$entity) {
            throw new \Exception('Entity not found');
        }
        $relation = $resource->getRelationshipByJsonName($relationship);
        if (!$relation) {
            throw new \Exception('Relationship not found');
        } elseif (!($relation instanceof \TyHand\JsonApiToolsBundle\ApiResource\HasManyRelationship)) {
            throw new \Exception('Method is only for Has Many Relationships');
        }

        if ($resource->getUseVoters()) {
            $this->denyAccessUnlessGranted($resource->getVoterEditAttribute(), $entity);
        }

        $relation->setModeToRemove();
        $entity = $relation->addToEntity($entity, json_decode($request->getContent(), true), $this->get('jsonapi_tools.resource_manager'));

        $errors = $resource->validate($entity, $this->get('validator'));
        if (0 < count($errors)) {
            return $this->createErrorResponse($errors);
        }

        $this->getDoctrine()->getManager()->flush();

        $json = ['data' => $relation->getResourceIdentifierJson($entity)];

        return new JsonResponse($this->postProcessJson($request, $json));
    }


    /**
     * Gets the name of the resource, which is taken from the controller name by default
     * @return string Resource name
     */
    public function getResourceName()
    {
        preg_match('/(\w+)Controller$/', get_class($this), $matches);
        if (isset($matches[1])) {
            return Inflect::pluralize(strtolower($matches[1]));
        } else {
            return null;
        }
    }

    /**
     * Generate the error response from an array or errors
     * @param  array        $errors Error array
     * @return JsonResponse         Response
     */
    protected function createErrorResponse($errors)
    {
        $json = [];
        foreach($errors as $error) {
            $json[] = $error->toJson();
        }
        return new JsonResponse(['errors' => $json], 422);
    }

    /**
     * Creates the includes manager
     * @param  Request        $request Http Request
     * @return IncludeManager          Include Manager
     */
    protected function createIncludesManager(Request $request)
    {
        if ($request->query->has('include')) {
            return new IncludeManager($this->get('jsonapi_tools.resource_manager'), explode(',', $request->query->get('include')));
        } else {
            return new IncludeManager($this->get('jsonapi_tools.resource_manager'));
        }
    }

    /**
     * Create the link generator
     * @param  Request       $request Request
     * @return LinkGenerator          Link Generator
     */
    protected function createLinkGenerator(Request $request)
    {
        return new LinkGenerator($request);
    }

    /**
     * Add final touches to the json output
     * @param  Request $request Request
     * @param  array   $json    Json hash
     * @return array            Altered Json hash
     */
    protected function postProcessJson(Request $request, $json)
    {
        $json['jsonapi'] = ['version' => '1.0'];

        return $json;
    }
}
