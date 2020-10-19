<?php

namespace NanoContainer;

use Closure;

interface Factory
{

    public function register(string $id, Closure $closure): void;

    public function set(string $id, $value): void;

    public function addProvider(FactoryProvider $provider): void;

    public function createContainer(): Container;
}
