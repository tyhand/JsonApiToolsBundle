<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

use Symfony\Component\HttpFoundation\Request;

class LinkGenerator
{
    /**
     * Request Copy
     * @var Request
     */
    private $request;

    /**
     * Constructor
     * @param Request $request Request
     */
    public function __construct(Request $request)
    {
        $this->request = clone $request;
    }

    /**
     * Create the first, prev, next, last pagination links
     * @param  FindResult $result Result object from the find operation
     * @return array              Json Hash for the links part of the document
     */
    public function generatePaginationLinks(FindResult $result)
    {
        $links = ['self' => $this->request->getUri()];

        $size = $result->getPageSize();
        $number = $result->getPageNumber();
        $last = intdiv($result->getCount(), $size);
        if (($result->getCount() % $size) > 0) {
            $last++;
        }

        $this->request->query->set('page', ['size' => $size, 'number' => 1]);
        $this->request->overrideGlobals();
        $links['first'] = $this->request->getUri();

        if ($number > 1) {
            $this->request->query->set('page', ['size' => $size, 'number' => $number - 1]);
            $this->request->overrideGlobals();
            $links['prev'] = $this->request->getUri();
        } else {
            $links['prev'] = null;
        }

        if ($number < $last) {
            $this->request->query->set('page', ['size' => $size, 'number' => $number + 1]);
            $this->request->overrideGlobals();
            $links['next'] = $this->request->getUri();
        } else {
            $links['next'] = null;
        }

        $this->request->query->set('page', ['size' => $size, 'number' => $last]);
        $this->request->overrideGlobals();
        $links['last'] = $this->request->getUri();

        return $links;
    }
}
