<?php

/**
 * Created by PhpStorm.
 * User: Koen
 * Date: 5-7-2017
 * Time: 21:59
 */


class Database
{
    var $pdo;

    function __construct()
    {
        $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    }

    function wordExists(string $word, string $language) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM words WHERE word = :word AND language = :lang');
            $stmt->bindParam(':word', $word);
            $stmt->bindParam(':lang', $language);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return true;
            }else{
                return false;
            }
        }catch (PDOException $e){
            echo "wordExists failed.";
            die();
        }
    }

    function getRandomWord(int $letters, string $language) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM words WHERE characters = :letters AND `language` = :lang AND sane = 1 ORDER BY RAND() LIMIT 1");
            $stmt->bindParam(':letters', $letters);
            $stmt->bindParam(':lang', $language);
            $stmt->execute();

            return $stmt->fetch()["word"];
        }catch (PDOException $e){
            echo "getRandomWord failed.";
            die();
        }
    }
}