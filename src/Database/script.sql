CREATE DATABASE desafio_aba;

USE desafio_aba;

CREATE TABLE funcionario (
	id INT AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    funcao VARCHAR(50) NOT NULL,
    data_nascimento DATE NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE departamento (
    id INT AUTO_INCREMENT,
    descricao VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO 
    departamento (descricao) 
VALUES 
    ('TI'), 
    ('SUPORTE'), 
    ('DESIGN');

CREATE TABLE funcionario_departamento (
    id INT AUTO_INCREMENT,
    id_funcionario INT NOT NULL,
    id_departamento INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id) ON DELETE CASCADE,
    FOREIGN KEY (id_departamento) REFERENCES departamento(id) ON DELETE CASCADE
);