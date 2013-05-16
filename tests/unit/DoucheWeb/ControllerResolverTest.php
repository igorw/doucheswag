<?php

namespace tests\unit\DoucheWeb;

use Phake;
use DoucheWeb\ControllerResolver;
use Pimple;
use Symfony\Component\HttpFoundation\Request;

class ControllerResolverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->decoratedResolver = Phake::mock("Symfony\Component\HttpKernel\Controller\ControllerResolverInterface");
        $this->container = new Pimple();
        $this->resolver = new ControllerResolver($this->decoratedResolver, $this->container);
    }

    /** @test */
    public function getControllerShouldReturnService()
    {
        $this->container['my_callable_controller'] = $this->container->protect(function () {});

        $request = Request::create("/");
        $request->attributes->set('_controller', "my_callable_controller");

        $controller = $this->resolver->getController($request);

        $this->assertSame($this->container['my_callable_controller'], $controller);
    }

    /** @test */
    public function getControllerShouldDeferIfControllerNotAString()
    {
        $request = Request::create("/");
        $request->attributes->set('_controller', function () {});

        $this->resolver->getController($request);

        Phake::verify($this->decoratedResolver)->getController($request);
    }

    /** @test */
    public function getControllerShouldDeferIfControllerIsStringButNotInApp()
    {
        $request = Request::create("/");
        $request->attributes->set('_controller', "non_existant_service");

        $this->resolver->getController($request);

        Phake::verify($this->decoratedResolver)->getController($request);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Service "my_string" is not callable.
     */
    public function getControllerShouldThrowIfServiceNotCallable()
    {
        $this->container['my_string'] = "dave";

        $request = Request::create("/");
        $request->attributes->set('_controller', "my_string");

        $this->resolver->getController($request);
    }

    /** @test */
    public function getArgumentsShouldDeferToDecoratedResolver()
    {
        $request = Request::create("/");

        $this->resolver->getArguments($request, $controller = function () {});

        Phake::verify($this->decoratedResolver)->getArguments($request, $controller);
    }
}
