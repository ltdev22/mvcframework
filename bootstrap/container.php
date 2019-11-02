<?php

// Create a new container instance
$container = new \League\Container\Container;

// Now that we have a container instance we can add
// and also pull back out things to it
//
// Since the dependencies are registered within the container in the service providers
// there's no need to inject every single one from the routes.php to controller
// (see @https://github.com/ltdev22/mvcframework/commit/efdf2d0b6522481c757f242e6f4f51e5d2af518d)
// As long as they get typehinted in the controller constructor auto-wiring is taking care of this ;-)
$container->delegate(new \League\Container\ReflectionContainer());

$container->addServiceProvider(new \App\Providers\AppServiceProvider());
$container->addServiceProvider(new \App\Providers\ViewServiceProvider());
