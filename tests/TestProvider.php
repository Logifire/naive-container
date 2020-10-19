<?php

namespace NanoContainer\Test;

use NanoContainer\Container;
use NanoContainer\ContainerFactory;
use NanoContainer\FactoryProvider;

class TestProvider implements FactoryProvider
{

    const PROVIDER_VALUE = 'provider_value';
    const PROVIDER_SERVICE = 'provider_service';
    const EXPECTED_VALUE = 12;

    public function register(ContainerFactory $factory): void
    {
        $factory->set(self::PROVIDER_VALUE, self::EXPECTED_VALUE);

        $factory->register(self::PROVIDER_SERVICE,
            function(Container $container) {
            return $container->get(self::PROVIDER_VALUE);
        });
    }
}
