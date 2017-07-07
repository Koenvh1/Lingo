<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

session_start();

require_once "../includes.php";
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

$i18n = new i18n('../lang/lang_{LANGUAGE}.ini', '../cache/', 'en');

// Language middleware
$languageMiddleware = function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
    global $i18n;
    $requestTarget = ltrim($request->getUri()->getPath(), "/");
    if(substr($requestTarget, 0,2) == "en"){
        $language = "en";
        $newPath = $request->getUri()->withPath(ltrim(str_replace("en", "", $requestTarget), "/"));
        $request = $request->withUri($newPath);
        //echo $request->getRequestTarget();
    } elseif (substr($requestTarget, 0,2) == "nl") {
        $language = "nl";
        $newPath = $request->getUri()->withPath(ltrim(str_replace("nl", "", $requestTarget), "/"));
        $request = $request->withUri($newPath);
    } elseif (substr($requestTarget, 0,2) == "de") {
        $language = "de";
        $newPath = $request->getUri()->withPath(ltrim(str_replace("de", "", $requestTarget), "/"));
        $request = $request->withUri($newPath);
    } else {
        $cookie = isset($_COOKIE["language"]) ? $_COOKIE["language"] : null;
        $prefLocales = array_reduce(
            explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']),
            function ($res, $el) {
                list($l, $q) = array_merge(explode(';q=', $el), [1]);
                $res[$l] = (float) $q;
                return $res;
            }, []);
        arsort($prefLocales);
        if($cookie == "nl"){
            $language = "nl";
        } elseif($cookie == "en"){
            $language = "en";
        } elseif($cookie == "de"){
            $language = "de";
        } else {
            $language = "en";
            foreach ($prefLocales as $key => $value){
                if(substr($key, 0, 2) == "nl"){
                    $language = "nl";
                    break;
                } elseif (substr($key, 0, 2) == "en") {
                    $language = "en";
                    break;
                } elseif (substr($key, 0, 2) == "de") {
                    $language = "de";
                    break;
                }
            }
        }
    }
    $i18n->setForcedLang($language);
    $i18n->init();
    setcookie("language", $language, time() + (86400 * 365), "/");
    $request = $request->withAttribute("language", $language);
    return $next($request, $response);
};
$app->add($languageMiddleware);


// Register default routes
/*
$app->get("/[home]", function (ServerRequestInterface $request, ResponseInterface $response, $args) {
    return $this->view->render($response, "home", [
        "language" => $request->getAttribute("language"),
        "page" => ltrim($request->getUri()->getPath())
    ]);
});
*/

$app->get("/", \Controllers\LingoController::class . ":view");

$app->post("/play/check", \Controllers\LingoController::class . ":check");
$app->post("/play/init", \Controllers\LingoController::class . ":init");
$app->post("/play/right", \Controllers\LingoController::class . ":right");

// Run app
$app->run();