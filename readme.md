# Desafio ABA - Estágio 2021

## Desafio

- Crie um CRUD de funcionários onde seja possível inserir, listar, editar e deletar os funcionarios ja cadastrados.
- Os funcionários devem conter as informações: nome, funcao e idade.
- Cada funcionario pertence a um departamento (TI, SUPORTE e DESIGN) que somente possui um atributo: nome.
- Tecnologias: PHP e MariaDB.

## Instalação

Via Composer

``` bash
$ composer install
```

## Rotas de Funcionário

### Cadastrar

    POST: /api/funcionario.php

    {
        "nome": "Vitória",
        "id_dep": 1,
        "funcao": "Desenvolvedor de Software",
        "data_nascimento": "01/04/1999"
    }

    Campos Obrigatórios:
    -> Nome
    -> Código do Departamento
    -> Função do Funcionário
    -> Data de Nascimento

### Listar 

    GET: /api/funcionario.php (listar todos os registros)
    GET: /api/funcionario.php?id=1 (listar um registro especifíco)

### Atualizar/Editar 

    PUT: /api/funcionario.php?id=1 

    OBS.: Para uso da rota de atualização, é OBRIGATÓRIO informar o id do registro para realizar a ação.

    {
        "nome": "Vickie",
        "id_dep": 2,
        "funcao": "Suporte Técnico",
        "data_nascimento": "01/04/1998"
    }

    OBS².: Para atualização do registro, é necessário que seja informado ao menos um campo. 
    Não há campos obrigatórios, e devem ser informados apenas os campos que terão informações para atualizar. 
    Exemplo acima mostra TODOS os campos que podem ser atualizados.

### Deletar

    DELETE: /api/funcionario.php?id=1 

    OBS.: Para uso da rota de atualização, é OBRIGATÓRIO informar o id do registro para realizar a ação.



## Rotas de Departamento

### Cadastrar

    POST: /api/departamento.php

    {
        "descricao": "Banco de Dados"
    }

    Campos Obrigatórios:
    -> Descrição

### Listar 

    GET: /api/departamento.php (listar todos os registros)
    GET: /api/departamento.php?id=1 (listar um registro especifíco)

### Atualizar/Editar 

    PUT: /api/departamento.php?id=1 

    OBS.: Para uso da rota de atualização, é OBRIGATÓRIO informar o id do registro para realizar a ação.

    {
        "descricao": "RH"
    }

    OBS².: O campo de descrição, por ser único e ser necessário informar 
    ao menos um campo para atualização de registros, é obrigatório.

### Deletar

    DELETE: /api/departamento.php?id=1 

    OBS.: Para uso da rota de atualização, é OBRIGATÓRIO informar o id do registro para realizar a ação.

## Observações e Considerações

Tomei a liberdade de implementar algumas funcionalidades, validações e melhorias. Segue abaixo algumas observações:

- Não salvamos dados inconscientes, como **idade**, no Banco, portanto solicitei a data de nascimento no lugar, porém a rota de listar os registros retorna a idade, conforme cálculo feito na própria consulta ao banco implementada à rota;

- **TODOS** os dados recebidos são tratados e validados. Foi criado uma classe contendo algumas funções para a verificação dos dados recebidos, como datas válidas, campos numéricos, campos obrigatórios e mínimo e máximo de caracteres;

- Foi criado também um CRUD para os Departamentos, uma vez que as rotas de Funcionários necessitam do index dos departamentos para realização de algumas funcionalidades e também para tornar os departamentos dinâmicos;

- Conforme a modelagem da base de dados, encontrada em **"src/database/script.sql"**, foi criado uma tabela para a ligação do funcionário ao departamento a fim de realizar uma maior abstração do banco/dados e ser maleável para uma possível modificação futura, caso o funcionário possa ter mais de um departamento.
    
Meus agradecimentos a Rafael Douglas e Guilherme Carvalho pelo tempo depositado em mim e a oportunidade oferecida.
