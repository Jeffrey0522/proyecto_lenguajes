<?php

class IndexController {

    private $view;

    public function __construct()
    {
        $this->view = new View();
    } // constructor

    public function mostrar()
    {
        $this->view->show("indexView.php", null);
    } // listar
     
} // fin clase