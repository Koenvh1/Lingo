<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LingoController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function view(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $database = new \Database();
        $_SESSION["word"] = $database->getRandomWord($args["letters"], $request->getAttribute("language"));

        $aidLetters = [];
        for($i = 0; $i < $args["letters"]; $i++) {
            if($i == 0) {
                $aidLetters[$i] = str_split($_SESSION["word"])[$i];
            } else {
                $aidLetters[$i] = null;
            }
        }

        return $this->container->view->render($response, "play", [
            "language" => $request->getAttribute("language"),
            "page" => ltrim($request->getUri()->getPath()),
            "letters" => $args["letters"],
            "aidLetters" => $aidLetters
        ]);
    }

    public function check(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $guess = strtoupper($_POST["word"]);
        $rightWord = strtoupper($_SESSION["word"]);
        $resultArray = [
            "letters" => [],
            "error" => null,
            "win" => false
        ];

        $database = new \Database();
        if($database->wordExists($guess, $request->getAttribute("language"))) {

            $rightWordArray = str_split($rightWord);

            for ($i = 0; $i < strlen($rightWord); $i++) {
                if ($guess[$i] == $rightWord[$i]) {
                    $resultArray["letters"][$i] = 2;
                } elseif (in_array($guess[$i], $rightWordArray) && $guess[strpos($rightWord, $guess[$i])] !== $rightWord[strpos($rightWord, $guess[$i])]) {
                    $resultArray["letters"][$i] = 1;
                    unset($rightWordArray[array_search($guess[$i], $rightWordArray)]);
                } else {
                    $resultArray["letters"][$i] = 0;
                }
            }
        } else {
            $resultArray["error"] = "This word does not exist!";
        }

        if($rightWord == $guess){
            $resultArray["win"] = true;
        }

        return $response->getBody()->write(json_encode($resultArray));
    }
}