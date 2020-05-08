<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;


$app = new \Slim\App(['settings' => $config]);
/*$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "secure" => false,
    "path" => "/admin", //["/admin", "/api"]
    "realm" => "Protected",
    "users" => [
        "root" => "toot"
    ]
]));*/

$container = $app->getContainer();

// rich helpers and globals
require_once('helpers/helpers.php');

$container['db'] = function ($c) {
    $db = new MysqliDb ('localhost', 'connect', 'gol4TOs7Ta', 'connect');
    return $db;
};

$container['view'] = new \Slim\Views\PhpRenderer('templates/');

/* standard routes */
// send messages
$app->get('/', function (Request $request, Response $response) {
    $response = $this->view->render($response, 'index.phtml');
    //$response->getBody()->write("Hello");
    return $response;
});
// get messages
$app->get('/messages', function (Request $request, Response $response) {
    $response = $this->view->render($response, 'messages.phtml');
    //$response->getBody()->write("Hello");
    return $response;
});

// messages api
$app->post('/api/messages', function (Request $request, Response $response) {
    return include('api/messages.php');
});



$app->run();
