<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

session_start();

require_once "./includes.php";
// Create and configure Slim app
$app = new \Slim\App(["settings" => [
    "displayErrorDetails" => true,
    "addContentLengthHeader" => false,
]]);

$container = $app->getContainer();

$container["renderer"] = new PhpRenderer("../templates");

/*
$container["notFoundHandler"] = function ($container) {
    return function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
        $container['response']->withStatus(404);
        $response = $container->view->render($response, $request->getAttribute("language") . "/404", [
            "language" => $request->getAttribute("language"),
            "page" => ltrim($request->getUri()->getPath())
        ]);
        $response = $response->withStatus(404);
        return $response;
    };
};
*/

/*
$app->get("/", \Controllers\LingoController::class . ":view");

$app->get("/multiplayer", function (ServerRequestInterface $request, ResponseInterface $response, $args) {
    return $this->renderer->render($response, "/multiplayer.php", [
        "language" => $request->getAttribute("language"),
        "page" => ltrim($request->getUri()->getPath())
    ]);
});
$app->get("/start", function (ServerRequestInterface $request, ResponseInterface $response, $args) {
    return $this->renderer->render($response, "/start.php", [
        "language" => $request->getAttribute("language"),
        "page" => ltrim($request->getUri()->getPath())
    ]);
});
$app->get("/game", function (ServerRequestInterface $request, ResponseInterface $response, $args) {
    return $this->renderer->render($response, "/game.php", [
        "language" => $request->getAttribute("language"),
        "page" => ltrim($request->getUri()->getPath())
    ]);
});
$app->get("/login", function (ServerRequestInterface $request, ResponseInterface $response, $args) {
    return $this->renderer->render($response, "/login.php", [
        "language" => $request->getAttribute("language"),
        "page" => ltrim($request->getUri()->getPath())
    ]);
});
*/

$app->post("/check", \Controllers\LingoController::class . ":check");
$app->post("/init", \Controllers\LingoController::class . ":init");
$app->post("/right", \Controllers\LingoController::class . ":right");

// Run app
$app->run();