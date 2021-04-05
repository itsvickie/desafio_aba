<?php

namespace Desafioaba\App\Controllers;

use Desafioaba\Database\Db;
use Desafioaba\Services\Validation;

class FuncionarioController extends Db
{
    public function insert()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        $validation = new Validation();

        $nome = $validation->required($body['nome'], 'Nome');
        $id_dep = $validation->num($body['id_dep'], 'Código do Departamento');
        $funcao = $validation->required($body['funcao'], 'Função');
        $data_nascimento = $validation->birthdate($body['data_nascimento']);

        $conn = $this->connect();
        $sql = $conn->prepare("SELECT id FROM departamento WHERE id = ?");
        $sql->bind_param("i", $id_dep);
        $sql->execute();
        $sql = $sql->get_result();
        $sql = $sql->fetch_assoc();

        if(!$sql){
            http_response_code(500);
            die(json_encode(['message' => 'Departamento informado não cadastrada!']));
        }

        $sql = $conn->prepare("INSERT INTO funcionario (nome, funcao, data_nascimento) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $nome, $funcao, $data_nascimento);

        if($sql->execute()){
            $id_funcionario = $conn->insert_id;
            $sql = $conn->prepare("INSERT INTO funcionario_departamento (id_funcionario, id_departamento) VALUES (?, ?)");
            $sql->bind_param("ii", $id_funcionario, $id_dep);

            if($sql->execute()){
                http_response_code(201);
                die(json_encode(['message' => 'Funcionário cadastrado com sucesso!']));
            }
        }

        http_response_code(500);
        die(json_encode(['message' => 'Não foi possível cadastrar o funcionário!']));
    }

    public function select()
    {
        $conn = $this->connect();
        $base = "   SELECT 
                        f.id,
                        f.nome AS nome,
                        DATE_FORMAT(f.data_nascimento, '%d/%m/%Y') AS data_nasc,
                        YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(f.data_nascimento))) AS idade,
                        f.funcao AS funcao,
                        d.id AS id_dep,
                        d.descricao AS departamento

                    FROM 
                        funcionario f 

                    LEFT JOIN
                        funcionario_departamento fd ON
                        fd.id_funcionario = f.id
                    
                    LEFT JOIN
                        departamento d ON
                        d.id = fd.id_departamento";

        if(isset($_GET['id'])){
            $sql = $conn->prepare($base . " WHERE f.id = ?");
            $sql->bind_param('i', $_GET['id']);
            $sql->execute();
            $sql = $sql->get_result();
            $sql = $sql->fetch_assoc();
            
            if($sql){
                http_response_code(200);
                die(json_encode($sql));
            }

            http_response_code(500);
            die(json_encode(['message' => 'Funcionário não encontrado!']));
        }

        $sql = $conn->query($base)->fetch_all(MYSQLI_ASSOC);

        if($sql){
            http_response_code(200);
            die(json_encode($sql));
        }

        http_response_code(500);
        die(json_encode(['message' => 'Não foi possível buscar os dados!']));
    }

    public function update()
    {
        if(!isset($_GET['id'])){
            http_response_code(500);
            die(json_encode(['message' => 'Código do Funcionário não informado!']));
        }

        $body = json_decode(file_get_contents("php://input"), true);
        $validation = new Validation();

        $id_dep = isset($body['id_dep']) ? $validation->num($body['id_dep'], 'Código do Departamento') : false;

        $conn = $this->connect();

        if($id_dep){
            $sql = $conn->prepare("SELECT id FROM departamento WHERE id = ?");
            $sql->bind_param('i', $id_dep);
            $sql->execute();
            $sql = $sql->get_result();
            $sql = $sql->fetch_assoc();
    
            if(!$sql){
                http_response_code(500);
                die(json_encode(['message' => 'Código do Departamento Inválido!']));
            }
        }

        $id = $validation->num($_GET['id'], 'Código do Funcionário');
        $nome = isset($body['nome']) ? $validation->required($body['nome'], 'Nome') : false;
        $funcao = isset($body['funcao']) ? $validation->required($body['funcao'], 'Função') : false;
        $data_nascimento = isset($body['data_nascimento']) ? $validation->birthdate($body['data_nascimento']) : false;

        $arr = [];
        $params = '';
        $sql = "UPDATE funcionario SET";

        if($nome){
            $sql .= " nome = ?";
            $params .= 's';
            array_push($arr, $nome);
        }

        if($funcao){
            $sql .= $nome ? ", funcao = ?" : " funcao = ?";
            $params .= 's';
            array_push($arr, $funcao);
        }

        if($data_nascimento){
            if($nome || $funcao){
                $sql .= ", data_nascimento = ? ";
            } else {
                $sql .= " data_nascimento = ? ";
            }

            $params .= 's';
            array_push($arr, $data_nascimento);
        }

        if(!empty($arr)){
            array_push($arr, $id);
            $params .= 'i';

            $sql = $conn->prepare($sql . " WHERE id = ?");
            $sql->bind_param($params, ...$arr);
            $sql->execute();

            if(!$id_dep){
                http_response_code(200);
                die(json_encode(['message' => 'Funcionário atualizado com sucesso!']));
            }
        }

        $sql = $conn->prepare("UPDATE funcionario_departamento SET id_departamento = ? WHERE id_funcionario = ?");
        $sql->bind_param("ii", $id_dep, $id);

        if($sql->execute()){
            http_response_code(200);
            die(json_encode(['message' => 'Funcionário atualizado com sucesso!']));
        } 

        http_response_code(500);
        die(json_encode(['message' => 'Não foi possível atualizar o funcionário!']));
    }

    public function delete()
    {
        if(!isset($_GET['id'])){
            http_response_code(500);
            die(json_encode(['message' => 'Código do Funcionário Não Informado!']));
        }

        $validation = new Validation();
        $conn = $this->connect();

        $id = $validation->num($_GET['id'], 'Código do Funcionário');

        $sql = $conn->prepare("DELETE FROM funcionario WHERE id = ?");
        $sql->bind_param('i', $id);
        
        if($sql->execute()){
            http_response_code(200);
            die(json_encode(['message' => 'Funcionário deletado com sucesso!']));
        }

        http_response_code(500);
        die(json_encode(['message' => 'Não foi possível deletar o funcionário!']));
    }
}