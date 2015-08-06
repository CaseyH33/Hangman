<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Hangman.php";

    session_start();
    if(empty($_SESSION['list_of_hangmans'])) {
        $_SESSION['list_of_hangmans'] = array();
        $gameWords = array("dog", "fish", "php", "epicodus", "programming",
                           "computer", "shoes", "building", "airplane", "bibulous",
                           "braggadocio", "onomatopoeia", "hypnopompic");
        $gameWord= new Hangman($gameWords[rand(0, strlen($gameWords)]);
        array_push($_SESSION['list_of_hangmans'], $gameWord);
    }

    $app = new Silex\Application();
    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));
    $app->get("/", function() use ($app) {
        return $app['twig']->render('main_page.html.twig', array('theGame' => Hangman::getAll()));
    });
    $app->get("/dead", function() use ($app) {
        return $app['twig']->render('game_over.html.twig');
    });
    $app->post("/guessWord", function() use ($app) {
        if($_SESSION['list_of_hangmans'][0]->checkWord($_POST['wordGuess'])) {
            return $app['twig']->render('win.html.twig');
        } else {
            $_SESSION['list_of_hangmans'][0]->wrongGuess();
            if($_SESSION['list_of_hangmans'][0]->checkGameOver) {
                return $app['twig']->render('game_over.html.twig');
            } else {
                return $app['twig']->render('main_page.html.twig', array('theGame' => Hangman::getAll()));

            }

        }
    });

    $app->post("/guessLetter", function() use ($app) {

    });
?>
