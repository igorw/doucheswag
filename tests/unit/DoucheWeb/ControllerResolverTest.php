<?php

namespace tests\unit\DoucheWeb;

use Phake;
use DoucheWeb\ControllerResolver;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ControllerResolverTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->decoratedResolver = Phake::mock("Symfony\Component\HttpKernel\Controller\ControllerResolverInterface");
        $this->app = new Application();
        $this->resolver = new ControllerResolver($this->decoratedResolver, $this->app);
    }

    /** @test */
    public function getControllerShouldReturnService()
    {
        $this->app['my_callable_controller'] = $this->app->protect(function () {});

        $request = Request::create("/");
        $request->attributes->set('_controller', "my_callable_controller");

        $controller = $this->resolver->getController($request);

        $this->assertSame($this->app['my_callable_controller'], $controller);
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
        $this->app['my_string'] = "dave";

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
