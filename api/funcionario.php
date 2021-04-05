<?php

require __DIR__ . '/../vendor/autoload.php';

use Desafioaba\App\Controllers\FuncionarioController;

$method = strtolower($_SERVER['REQUEST_METHOD']);

$controller = new FuncionarioController;

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