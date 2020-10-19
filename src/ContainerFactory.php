<?php

namespace NanoContainer;

use Closure;

class ContainerFactory extends ContainerDecorator implements Factory
{

    /**
     * @var Container
     */
    private $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * @var mixed [id => value] May be simple types or closures
     */
    protected $factory_stack = [];

    /**
     * @param string  $id      Example::class
     * @param Closure $closure function(Container $c) {...return $example;}
     * 
     * @return void
     */
    public function register(string $id, Closure $closure): void
    {
        $this->factory_stack[$id] = $closure;
    }

    /**
     * @param string $id    Example::class
     * @param type   $value $example
     * 
     * @return void
     */
    public function set(string $id, $value): void
    {
        $this->factory_stack[$id] = $value;
    }

    /**
     * Configure an instance which has been registered or set.
     * 
     * @see self::register
     * @see self::set
     * 
     * @param string $id       Example::class
     * @param Closure $closure function(Container $c) {...return $example;}
     * 
     * @return void
     */
    public function configure(string $id, Closure $closure): void
    {
        $this->container->container_stack = $this->factory_stack;
        $this->factory_stack[$id] = call_user_func($closure, $this->container);
    }

    public function addProvider(FactoryProvider $provider): void
    {
        $provider->register($this);
    }

    public function createContainer(): Container
    {
        $container = new Container();
        $container->container_stack = $this->factory_stack;

        return $container;
    }
}
