<?php

use Slim\Container;

class Controller
{
    protected $view;
    protected $logger;
    protected $flash;
    protected $errorHandler;

    public function __construct(Container $c)
    {
        $this->view = $c->get('view');
        $this->logger = $c->get('logger');
        $this->flash = $c->get('flash');
        $this->errorHandler = $c->get('errorHandler');
    }


}