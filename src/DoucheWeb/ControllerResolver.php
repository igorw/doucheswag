<?php

namespace DoucheWeb;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class ControllerResolver implements ControllerResolverInterface
{
    protected $resolver;
    protected $app;

    public function __construct(ControllerResolverInterface $resolver, Application $app)
    {
        $this->resolver = $resolver;
        $this->app = $app;
    }

    public function getController(Request $request)
    {
        $controller = $request->attributes->get('_controller', null);

        if (!is_string($controller) || !isset($this->app[$controller])) {
            return $this->resolver->getController($request);
        }

        if (!is_callable($this->app[$controller])) {
            throw new \InvalidArgumentException(sprintf('Service "%s" is not callable.', $controller));
        }

        return $this->app[$controller];
    }

    public function getArguments(Request $request, $controller)
    {
        return $this->resolver->getArguments($request, $controller);
    }
}
