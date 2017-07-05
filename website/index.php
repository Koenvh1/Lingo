<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

session_start();

require_once "../includes.php";
// Create and configure Slim app
$app = new \Slim\App(["settings" => [
    "displayErrorDetails" => true,
    "addContentLengthHeader" => false,
    "renderer"            => [
        "blade_template_path" => "../templates", // String or array of multiple paths
        "blade_cache_path"    => "../cache", // Mandatory by default, though could probably turn caching off for development
    ],
]]);

$container = $app->getContainer();

// Define blade template engine
$container["view"] = function ($container) {
    return new \Slim\Views\Blade(
        $container["settings"]["renderer"]["blade_template_path"],
        $container["settings"]["renderer"]["blade_cache_path"]
    );
};

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

// Language middleware
$languageMiddleware = function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
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
    setcookie("language", $language, time() + (86400 * 365), "/");
    $request = $request->withAttribute("language", $language);
    return $next($request, $response);
};
$app->add($languageMiddleware);


// Register default routes
$app->get("/[home]", function (ServerRequestInterface $request, ResponseInterface $response, $args) {
    return $this->view->render($response, "home", [
        "language" => $request->getAttribute("language"),
        "page" => ltrim($request->getUri()->getPath())
    ]);
});
$app->get("/play/{letters}", \Controllers\LingoController::class . ":view");

$app->post("/play/check", \Controllers\LingoController::class . ":check");

// Run app
$app->run();