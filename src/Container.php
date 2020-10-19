<?php

namespace NanoContainer;

use NanoContainer\Exceptions\ContainerException;
use NanoContainer\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use Exception;

class Container extends ContainerDecorator implements ContainerInterface
{

    /**
     * Instantiated parts
     * 
     * @var mixed 
     */
    protected $instances = [];

    /**
     * @var bool[id] 
     */
    protected $call_stack = [];

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException("Couldn't find {$id}");
        }

        if (is_callable($this->container_stack[$id])) {
            // Performance optimization, isset returns false on null values, but is faster
            // https://stackoverflow.com/a/9522522/10604655
            if (!isset($this->instances[$id]) && !array_key_exists($id,
                    $this->instances)) {

                if (isset($this->call_stack[$id])) {
                    throw new ContainerException("Circular dependency detected when calling {$id}");
                }

                $this->call_stack[$id] = true;

                try {
                    $this->instances[$id] = call_user_func($this->container_stack[$id],
                        $this);
                } catch (Exception $e) {
                    throw new ContainerException($e->getMessage() . " - Tried to call {$id}");
                }

                $this->call_stack = [];
            }

            return $this->instances[$id];
        }

        return $this->container_stack[$id];
    }

    public function has($id): bool
    {
        return (isset($this->container_stack[$id]) || array_key_exists($id,
                $this->container_stack));
    }
}
