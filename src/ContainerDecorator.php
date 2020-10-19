<?php

namespace NanoContainer;

abstract class ContainerDecorator
{

    /**
     * @var mixed [id => value] May be simple types or closures
     */
    protected $container_stack = [];

}
