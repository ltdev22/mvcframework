<?php

// Create a new container instance
$container = new \League\Container\Container;

// Now that we have a container instance we can add
// and also pull back out things to it

$container->addServiceProvider(new \App\Providers\AppServiceProvider);
dump($container->get('test'));
