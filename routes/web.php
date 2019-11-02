<?php

$route->get('/', function ($request) {
    $response = new \Zend\Diactoros\Response;
    $response->getBody()->write('Hello World from routes!');
    return $response;
});
