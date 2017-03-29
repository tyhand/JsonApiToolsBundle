<?php

namespace TyHand\JsonApiToolsBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use TyHand\JsonApiToolsBundle\ApiResource\ResourceManager;

class ResourceRouteLoader extends Loader
{
    /**
     * Resource Manager
     * @var ResourceManager
     */
    private $manager;

    /**
     * Constructor
     * @param ResourceManager $manager Resource Manager
     */
    public function __construct(ResourceManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @{inheritDoc}
     */
    public function load($resource, $type= null)
    {
        if (!class_exists($resource, true)) {
            throw new \RuntimeException($resource . ' does not exist');
        }

        $controllerReflection = new \ReflectionClass($resource);
        if (!$controllerReflection->hasMethod('getResourceName')) {
            throw new \RuntimeException($resource . ' does not have the required method "getResourceName"');
        }

        $controller = $controllerReflection->newInstance();
        $resourceName = $controller->getResourceName();

        if (!$resourceName) {
            throw new \RuntimeException('Could not determine resource name for "'. $resource .'"');
        }

        // Load the api resource
        $apiResource = $this->manager->getResource($resourceName);
        if (!$apiResource) {
            throw new \RuntimeException('Could not load the resource "' . $resource . '"');
        }

        $routes = new RouteCollection();

        // Base Routes
        $routes->add($resourceName . '_index', $this->createRoute('/' . $resourceName, $resource . '::resourceIndexAction', [], ['GET']));
        $routes->add($resourceName . '_show', $this->createRoute('/' . $resourceName . '/{id}', $resource . '::resourceShowAction', ['id' => '\d+'], ['GET']));
        $routes->add($resourceName . '_create', $this->createRoute('/' . $resourceName, $resource . '::resourceCreateAction', [], ['POST']));
        $routes->add($resourceName . '_edit', $this->createRoute('/' . $resourceName . '/{id}', $resource . '::resourceEditAction', ['id' => '\d+'], ['PATCH']));

        // Add Delete if allowed
        if ($apiResource->getAllowDelete()) {
            $routes->add($resourceName . '_delete', $this->createRoute('/' . $resourceName . '/{id}', $resource . '::resourceDeleteAction', ['id' => '\d+'], ['DELETE']));
        }

        foreach($apiResource->getRelationships() as $name => $relationship) {
            $routes->add(
                $resourceName . '_show_relationship_' . $name,
                $this->createRoute('/' . $resourceName . '/{id}/relationships/' . $name , $resource . '::resourceShowRelationshipsAction', ['id' => '\d+'], ['GET'], ['relationship' => $name])
            );
            $routes->add(
                $resourceName . '_edit_relationship_' . $name,
                $this->createRoute('/' . $resourceName . '/{id}/relationships/' . $name , $resource . '::resourceEditRelationshipsAction', ['id' => '\d+'], ['PATCH'], ['relationship' => $name])
            );
            if (!$relationship->getRelationshipUrlOnly()) {
                $routes->add(
                    $resourceName . '_show_' . $name,
                    $this->createRoute('/' . $resourceName . '/{id}/' . $name , $resource . '::resourceShowRelationshipsFullAction', ['id' => '\d+'], ['GET'], ['relationship' => $name])
                );
            }

            if ($relationship instanceof \TyHand\JsonApiToolsBundle\ApiResource\HasManyRelationship) {
                $routes->add(
                    $resourceName . '_add_relationship_' . $name,
                    $this->createRoute('/' . $resourceName . '/{id}/relationships/' . $name , $resource . '::resourceAddRelationshipsAction', ['id' => '\d+'], ['POST'], ['relationship' => $name])
                );
                $routes->add(
                    $resourceName . '_remove_relationship_' . $name,
                    $this->createRoute('/' . $resourceName . '/{id}/relationships/' . $name , $resource . '::resourceRemoveRelationshipsAction', ['id' => '\d+'], ['DELETE'], ['relationship' => $name])
                );
            }
        }

        return $routes;
    }

    /**
     * @{inheritDoc}
     */
    public function supports($resource, $type = null)
    {
        return 'jsonapi_resource' === $type;
    }

    /**
     * Create a route
     * @param  string $pattern      Url pattern
     * @param  string $controller   Controller Action
     * @param  array  $requirements Requirements array
     * @param  array  $methods      Method array
     * @param  array  $defaults     Defaults
     * @return Route                New Route
     */
    private function createRoute($pattern, $controller, $requirements, $methods, $defaults = [])
    {
        $defaults['_controller'] = $controller;
        return new Route($pattern, $defaults, $requirements, [], '', [], $methods);
    }
}
