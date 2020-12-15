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
        
        $usuario = Container::getModel('usuario');
        $usuario->__set('id', $_SESSION['id']);

        $this->view->info_usuario = $usuario->getInfoUser();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();
        
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

    public function removerTweet(){
        $this->validateAuth();
        
        $tweet = Container::getModel('tweet');
        
        $tweet->__set('id', $_GET['id_tweet']);

        $tweet->remove();

        header('Location: /timeline');
    }

    public function quemSeguir(){
        $this->validateAuth();

        $usuario = Container::getModel('usuario');
        $usuario->__set('id', $_SESSION['id']);

        $this->view->info_usuario = $usuario->getInfoUser();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

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

    public function acao(){
        $this->validateAuth();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        if ($acao == 'seguir') {
            $usuario->seguirUsuario($id_usuario_seguindo);
        }else if($acao == 'deixar_de_seguir'){
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        header('Location: /quem_seguir');
    }

    public function validateAuth(){
        session_start();
        
        if (empty($_SESSION['id']) || empty($_SESSION['nome'])) {
            header('Location: /?login=erro');
        }
    }

}

?>