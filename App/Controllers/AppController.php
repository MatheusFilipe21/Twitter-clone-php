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

    public function quemSeguir(){
        $this->validateAuth();

        $pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

        $usuarios = array();

        if($pesquisa != ''){
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisa);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();
        }

        $this->view->usuarios = $usuarios;
        
        $this->render('quemSeguir');
    }

    public function validateAuth(){
        session_start();
        
        if (empty($_SESSION['id']) || empty($_SESSION['nome'])) {
            header('Location: /?login=erro');
        }
    }

}

?>