<?php

namespace Desafioaba\App\Controllers;

use Desafioaba\Database\Db;
use Desafioaba\Services\Validation;

class DepartamentoController extends Db
{
    public function insert()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        $validation = new Validation();

        $descricao = $validation->required($body['descricao'], 'Descrição');

        $conn = $this->connect();
        $sql = $conn->prepare("INSERT INTO departamento (descricao) VALUES (?)");
        $sql->bind_param('s', $descricao);

        if($sql->execute()){
            http_response_code(201);
            die(json_encode(['message' => 'Departamento cadastrado com sucesso!']));
        }

        http_response_code(500);
        die(json_encode(['message' => 'Não foi possível cadastrar o departamento!']));
    }

    public function select()
    {
        $conn = $this->connect();
        $sql = $conn->query("SELECT id, descricao FROM departamento")->fetch_all(MYSQLI_ASSOC);

        http_response_code(200);
        die(json_encode($sql));
    }

    public function update()
    {
        if(!isset($_GET['id'])){
            http_response_code(500);
            die(json_encode(['message' => 'Código do Departamento não informado!']));
        }

        $validation = new Validation();
        $body = json_decode(file_get_contents("php://input"), true);

        $id = $validation->num($_GET['id'], 'Código do Departamento');
        $descricao = $validation->required($body['descricao'], 'Descrição do Departamento');

        $conn = $this->connect();
        $sql = $conn->prepare("UPDATE departamento SET descricao = ? WHERE id = ?");
        $sql->bind_param('si', $descricao, $id);

        if($sql->execute()){
            http_response_code(200);
            die(json_encode(['message' => 'Departamento atualizado com sucesso!']));
        }

        http_response_code(500);
        die(json_encode(['message' => 'Não foi possível atualizar o departamento!']));
    }

    public function delete()
    {
        $validation = new Validation();

        $id = $validation->num($_GET['id'], 'Código do Departamento');

        $conn = $this->connect();
        $sql = $conn->prepare("DELETE FROM departamento WHERE id = ?");
        $sql->bind_param('i', $id);

        if($sql->execute()){
            http_response_code(200);
            die(json_encode(['message' => 'Departamento deletado com sucesso!']));
        }

        http_response_code(500);
        die(json_encode(['message' => 'Não foi possível deletar o departamento!']));
    }
}