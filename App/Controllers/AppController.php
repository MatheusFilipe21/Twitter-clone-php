<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{

    public function timeline(){
        $this->validateAuth();
            
        $tweet = Container::getModel('tweet');

        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweets = $tweet->getAll();

        $this->view->tweets = $tweets;
        
        $this->render('timeline');
    }

    public function tweet(){
        $this->validateAuth();
        
        $tweet = Container::getModel('tweet');
        
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->__set('tweet', $_POST['tweet']);

        $tweet->save();

        header('Location: /timeline');
    }

    public function validateAuth(){
        session_start();
        
        if (empty($_SESSION['id']) || empty($_SESSION['nome'])) {
            header('Location: /?login=erro');
        }
    }

}

?>