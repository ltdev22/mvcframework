<?php

namespace App\Wrappers;

use Twig\Environment as Twig;
use Zend\Diactoros\Response;

class View
{
    /**
     * Will hold the new Twig instance created in \App\Providers\ViewServiceProvider.
     *
     * @var this
     */
    protected $twig;

    /**
     * Create new View instance
     *
     * @param   \Twig    $twig  the twig instance
     * @return  void
     */
    function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render the view thats being injected to the controller
     * with any required data passed to it.
     *
     * @param  string   $view     the view template
     * @param  array    $data     array of data
     *
     * @return  Zend\Diactoros\Response
     */
    public function render(string $view, array $data = [])
    {
        $response = new Response();

        $response->getBody()->write(
            $this->twig->render($view, $data)
        );

        return $response;
    }

    /**
     * Array of data we need to share within out view.
     *
     * @param  array $data
     * @return [type]           [description]
     */
    public function share(array $data)
    {
        foreach ($data as $key => $value) {
            $this->twig->addGlobal($key, $value);
        }
    }
}