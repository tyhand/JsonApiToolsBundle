<?php

namespace TyHand\JsonApiToolsBundle\EventListener;

use TyHand\JsonApiToolsBundle\Controller\ResourceController;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResourceControllerListener
{
    /**
     * On Request
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        // Check for a resource controller
        if ($controller[0] instanceof ResourceController) {
            // Check that the action starts with resource
            if (1 === preg_match('/^resource/', $controller[1])) {
                if (!$event->getRequest()->headers->has('content-type') || $event->getRequest()->headers->get('content-type') !== 'application/vnd.api+json') {
                    throw new UnsupportedMediaTypeHttpException('Request to the json api must have the header "Content-Type: application/vnd.api+json"');
                } else {
                    $event->getRequest()->attributes->set('json_api_request', true);
                }
            }
        }
    }

    /**
     * On Response
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->getRequest()->attributes->get('json_api_request', false)) {
            return;
        }

        $event->getResponse()->headers->set('Content-Type', 'application/vnd.api+json');
    }
}
