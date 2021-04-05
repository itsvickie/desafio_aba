<?php

require '../vendor/autoload.php';

use Desafioaba\App\Controllers\DepartamentoController;

$method = strtolower($_SERVER['REQUEST_METHOD']);

$controller = new DepartamentoController;

switch($method){
    case 'post':
        $controller->insert();
        break;
    case 'get':
        $controller->select();
        break;
    case 'put':
        $controller->update();
        break;
    case 'delete':
        $controller->delete();
        break;
}