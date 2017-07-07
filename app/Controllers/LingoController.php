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

    /**
     * Render page
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return mixed
     */
    public function view(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        return $this->container->renderer->render($response, "/play.php", [
            "language" => $request->getAttribute("language"),
            "page" => ltrim($request->getUri()->getPath())
        ]);
    }

    /**
     * Check answer correct
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return int
     */
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
            /**
             * Dutch counts the IJ as one character, replace with | to solve double character issues
             */
            if($language == "nl") {
                $guess = str_replace("IJ", "|", $guess);
                $rightWord = str_replace("IJ", "|", $rightWord);
            }

            $rightWordArray = str_split($rightWord);

            /**
             * If letters are equal
             */
            for ($i = 0; $i < strlen($rightWord); $i++) {
                if ($guess[$i] == $rightWord[$i]) {
                    $resultArray["letters"][$i] = 2;
                    unset($rightWordArray[array_search($guess[$i], $rightWordArray)]); //Remove letter from the word array, so it doesn't become yellow too.
                }
            }

            /**
             * If letter is right, but not in the right place
             */
            for ($i = 0; $i < strlen($rightWord); $i++) {
                if ($guess[$i] != $rightWord[$i] && in_array($guess[$i], $rightWordArray)) { //If not the same place, but in the word.
                    $resultArray["letters"][$i] = 1;
                    unset($rightWordArray[array_search($guess[$i], $rightWordArray)]);
                }
            }
            //var_dump($rightWordArray);

            /**
             * Else, set to zero.
             */
            for ($i = 0; $i < strlen($rightWord); $i++) {
                if(!array_key_exists($i, $resultArray["letters"])) {
                    $resultArray["letters"][$i] = 0;
                }
            }

        } else {
            $resultArray["error"] = \L::game_doesNotExist($guess); //Can't find the word in database
        }

        if($rightWord == $guess){
            $resultArray["win"] = true;
        }

        return $response->getBody()->write(json_encode($resultArray));
    }

    /**
     * Get right word for the end of the game
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return int
     */
    public function right(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $resultArray = [
            "word" => $_SESSION["word"],
            "title" => \L::game_theRightWordIs
        ];
        return $response->getBody()->write(json_encode($resultArray));
    }

    /**
     * Initialize new game
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return int
     */
    public function init(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $language = $_POST["language"];
        $letters = $_POST["letters"];

        $database = new \Database();
        $_SESSION["word"] = $database->getRandomWord($letters, $language); //"BIJTEND";

        $aidLetters = [];
        if($language == "nl") {
            $word = str_replace("IJ", "|", $_SESSION["word"]); //Convert IJ to | if Dutch
        } else {
            $word = $_SESSION["word"];
        }

        $amount = $_POST["amount"];
        $first = ($_POST["first"] == "true");
        $wordArray = str_split($word);
        $randomLetters = array_rand($wordArray, $amount); //Pick random indexes from the word
        if(!is_array($randomLetters)) {
            $randomLetters = [$randomLetters]; //Make sure output is an array (with just one aid letter)
        }
        if($first && !in_array(0, $randomLetters)){
            array_pop($randomLetters);
            $randomLetters[] = 0;
        }
        //var_dump($wordArray);
        //var_dump($randomLetters);

        foreach($randomLetters as $letter) {
            $aidLetters[$letter] = ($wordArray[$letter] == "|" ? "IJ" : $wordArray[$letter]); //Convert | back to IJ
        }
        return $response->getBody()->write(json_encode($aidLetters));
    }
}