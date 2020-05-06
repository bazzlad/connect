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
    // prod check
    if (getcwd() == '/var/sites/q/quizzyrascal.co.uk/public_html') {
        $db = new MysqliDB ('10.169.0.221', 'quizzyra_quizzy' ,'qu1zzyRa5scal', 'quizzyra_quizzy');
    } else {
        $db = new MysqliDb ('localhost', 'quizzy', 'qu1zzyRa5scal', 'quizzy');
    }
    return $db;
};

$container['view'] = new \Slim\Views\PhpRenderer('templates/');


// standard routes
$app->get('/', function (Request $request, Response $response) {
    $response = $this->view->render($response, 'index.phtml');
    //$response->getBody()->write("Hello");
    return $response;
});
$app->get('/login', function (Request $request, Response $response) {
    $response = $this->view->render($response, 'login.phtml');
    //$response->getBody()->write("Hello");
    return $response;
});

// admin routes
$app->get('/admin/', function (Request $request, Response $response) {
    $response = $this->view->render($response, '/admin/index.phtml');
    return $response;
});
$app->get('/admin', function (Request $request, Response $response) {
    $response = $this->view->render($response, '/admin/index.phtml');
    return $response;
});
$app->get('/admin/users', function (Request $request, Response $response) {
    $this->db->orderBy('score', 'desc');
    $users = $this->db->get('users');
    $response = $this->view->render($response, '/admin/users.phtml', ['users' => $users]);
    return $response;
});
$app->get('/admin/answers', function (Request $request, Response $response) {
    $response = $this->view->render($response, '/admin/answers.phtml');
    return $response;
});
$app->get('/admin/questions', function (Request $request, Response $response) {
    $questions = $this->db->get('questions');

    // sort questions by questionId
    usort($questions, function ($item1, $item2) {
        return $item1['questionId'] <=> $item2['questionId'];
    });

    $response = $this->view->render($response, '/admin/questions.phtml', ['questions' => $questions]);
    return $response;
});
$app->get('/admin/question', function (Request $request, Response $response) {
    $questions = $this->db->get('question');
    $response = $this->view->render($response, '/admin/question.phtml', ['questions' => $questions]);
    return $response;
});



// users api
$app->post('/api/user', function (Request $request, Response $response) {
    return include('api/user.php');
});
$app->get('/api/users', function (Request $request, Response $response) {
    return include('api/users.php');
});
$app->get('/api/scores', function (Request $request, Response $response) {
    return include('api/scores.php');
});
// questions api
$app->get('/api/questions', function (Request $request, Response $response) {
    return include('api/questions.php');
});
// answers api
$app->post('/api/answer', function (Request $request, Response $response) {
    return include('api/answer.php');
});
// questions api
$app->get('/api/answers', function (Request $request, Response $response) {
    return include('api/answers.php');
});
$app->post('/api/question', function (Request $request, Response $response) {
    return include('api/question.php');
});
$app->post('/api/next-question', function (Request $request, Response $response) {
    return include('api/next-question.php');
});
// quiz api
$app->post('/api/quiz', function (Request $request, Response $response) {
    return include('api/quiz.php');
});



$app->run();
