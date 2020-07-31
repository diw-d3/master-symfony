<?php

namespace App\Listener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceListener
{
    private $enableMaintenance = false;
    private $parameters;

    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $route = $event->getRequest()->get('_route');

        if (null === $route || '_' === $route[0]) { // Pour le profiler
            return;
        }

        $fs = new Filesystem();
        $this->enableMaintenance = $fs->exists($this->parameters->get('kernel.project_dir').'/.maintenance');
    }

    public function onKernelController(ControllerEvent $event)
    {
        $route = $event->getRequest()->get('_route');

        if (null === $route || '_' === $route[0]) { // Pour le profiler
            return;
        }

        if (!$this->enableMaintenance) {
            return;
        }

        $event->setController(function (Request $request) {
            $response = new Response('
                <body>
                    <h1>Site en maintenance</h1>
                </body>
            ');
            $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);

            return $response;
        });
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$this->enableMaintenance) {
            return;
        }

        $response = new Response('
            <body>
                <h1>Site en maintenance</h1>
            </body>
        ');
        $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);

        $event->setResponse($response);
    }
}
