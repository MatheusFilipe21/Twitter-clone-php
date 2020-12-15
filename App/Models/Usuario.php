<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model{

    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($attr){
        return $this->$attr;
    }

    public function __set($attr, $value){
        $this->$attr = $value;
    }

    public function save(){
        $query = '
        INSERT INTO 
            `usuarios`(`nome`, `email`, `senha`)
        VALUES
            (:nome, :email, :senha)
        ';

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));

        $stmt->execute();

        return $this;
    }

    // Validar se um cadastro pode ser feito
    public function validate(){
        $valido = true;

        if (\strlen($this->__get('nome')) < 3) {
            $valido = false;
        }

        if (\strlen($this->__get('senha')) < 8) {
            $valido = false;
        }

        return $valido;
    }

    public function getUserForEmail(){
        $query = '
        SELECT
            `email`
        FROM
            `usuarios`
        WHERE
            email = :email
        ';

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':email', $this->__get('email'));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function auth(){

        $query = '
        SELECT
            `id`,`nome`,`email`
        FROM
            `usuarios`
        WHERE
            email = :email AND senha = :senha
        ';

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));

        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!empty($usuario['id']) && !empty($usuario['nome'])) {
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);
        }

        return $this;
    }

    public function getAll(){
        $query = '
        SELECT
            `id`,`nome`,`email`
        FROM
            `usuarios`
        WHERE
            nome LIKE :nome AND id != :id_usuario
        ';

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->bindValue(':id_usuario', $this->__get('id'));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

?>