<?php
namespace NaiveContainer;

use Closure;

abstract class Factory
{

    protected $factory_stack = [];

    public abstract function register($id, Closure $closure);

    public abstract function set($id, $value);

    public function addProvider(FactoryProvider $provider)
    {
        $provider->register($this);
    }

    public function createContainer()
    {
        $container = new Container();
        $bootstrap = function($stack) {
            $this->container_stack = $stack;
        };
        // Call the closure within the container scope
        $bootstrap->call($container, $this->factory_stack);

        return $container;
    }
}