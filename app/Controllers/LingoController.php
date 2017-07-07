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
        return $this->container->renderer->render($response, "/play.php", [
            "language" => $request->getAttribute("language"),
            "page" => ltrim($request->getUri()->getPath())
        ]);
    }

    public function check(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $guess = strtoupper($_POST["word"]);
        $rightWord = strtoupper($_SESSION["word"]);

        $language = $_POST["language"];

        $resultArray = [
            "letters" => [],
            "error" => null,
            "win" => false
        ];

        $database = new \Database();
        if($database->wordExists($guess, $language)) {
            if($request->getAttribute("language") == "nl") {
                $guess = str_replace("IJ", "|", $guess);
                $rightWord = str_replace("IJ", "|", $rightWord);
            }

            $rightWordArray = str_split($rightWord);

            for ($i = 0; $i < strlen($rightWord); $i++) {
                if ($guess[$i] == $rightWord[$i]) {
                    $resultArray["letters"][$i] = 2;
                    unset($rightWordArray[array_search($guess[$i], $rightWordArray)]);
                }
            }

            for ($i = 0; $i < strlen($rightWord); $i++) {
                if ($guess[$i] != $rightWord[$i] && in_array($guess[$i], $rightWordArray)) {
                    $resultArray["letters"][$i] = 1;
                    unset($rightWordArray[array_search($guess[$i], $rightWordArray)]);
                }
            }
            //var_dump($rightWordArray);

            for ($i = 0; $i < strlen($rightWord); $i++) {
                if(!array_key_exists($i, $resultArray["letters"])) {
                    $resultArray["letters"][$i] = 0;
                }
            }

        } else {
            $resultArray["error"] = \L::game_doesNotExist($guess);
        }

        if($rightWord == $guess){
            $resultArray["win"] = true;
        }

        return $response->getBody()->write(json_encode($resultArray));
    }

    public function right(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $resultArray = [
            "word" => $_SESSION["word"],
            "title" => \L::game_theRightWordIs
        ];
        return $response->getBody()->write(json_encode($resultArray));
    }

    public function init(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $language = $_POST["language"];
        $letters = $_POST["letters"];

        $database = new \Database();
        $_SESSION["word"] = $database->getRandomWord($letters, $language); //"BIJTEND";

        $aidLetters = [];
        if($language == "nl") {
            $word = str_replace("IJ", "|", $_SESSION["word"]);
        } else {
            $word = $_SESSION["word"];
        }

        $amount = $_POST["amount"];
        $first = $_POST["first"];
        $wordArray = str_split($word);
        $randomLetters = array_rand($wordArray, $amount);
        if($first && !in_array(0, $randomLetters)){
            array_pop($randomLetters);
            $randomLetters[] = 0;
        }
        //var_dump($wordArray);
        //var_dump($randomLetters);

        foreach($randomLetters as $letter) {
            $aidLetters[$letter] = ($wordArray[$letter] == "|" ? "IJ" : $wordArray[$letter]);
        }

        /*
        for($i = 0; $i < $amount; $i++) {
            if($i == 0) {
                $aidLetters[$i] = (str_split($word)[$i] == "|" ? "IJ" : str_split($word)[$i]);
            } else {
                $aidLetters[$i] = null;
            }
        }
        */

        return $response->getBody()->write(json_encode($aidLetters));
    }
}