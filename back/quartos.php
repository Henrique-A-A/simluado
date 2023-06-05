<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
//GET recebe/pega informaçõe
//POST envia informações
//PUT edita informações "update"
//DELETE deleta informações
//OPTIONS  é a  relação de methodos disponiveis para uso
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

include 'conexao.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //aqui eu crio o comando de select para consultar o banco
    $stmt = $conn->prepare("SELECT * FROM quartos");
    //aqui eu executo o select
    $stmt->execute();
    //aqui eu recebo os dados do banco por meio do PDO
    $quartos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //transformo os dados da variave $quartos em um JSON valido
    echo json_encode($quartos);
}

//Rota para criar quartos
//Rota para inserir quartos
//Utilizando o POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $tipo = $_POST['tipo'];
    $disponivel = $_POST['disponivel'];
    //inserir outros campos caso necessario ....

    $stmt = $conn->prepare("INSERT INTO quartos (numero, tipo, disponivel) VALUES (:numero, :tipo, :disponivel)");
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':disponivel', $disponivel);
    //Outros bindParams ....

    if ($stmt->execute()) {
        echo "quarto criado com sucesso!!";
    } else {
        echo "Erro ao criar quarto";
    }
}
// Rota para excluir um quarto

if($_SERVER['REQUEST_METHOD']==='DELETE' && isset($_GET['id'])){
    $id   = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM quartos WHERE id = :id");
    $stmt-> bindParam(':id', $id);

    if($stmt->execute()){
        echo "quarto excluido com sucesso!!!";
    } else {
        echo "Erro ao excluir quarto!";
    }
}

//Rota para atualizar um quarto existente
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    parse_str(file_get_contents("php://input"), $_PUT);
    $id = $_GET['id'];
    $novo_numero = $_PUT['numero'];
    $novo_tipo = $_PUT['tipo'];
    $novo_disponivel = $_PUT['disponivel'];
    //add novo_s campos caso necessario
    $stmt = $conn->prepare("UPDATE quartos SET numero = :numero, tipo = :tipo, disponivel = :disponivel WHERE id = :id");
    $stmt->bindParam(':numero', $novo_numero);
    $stmt->bindParam(':tipo', $novo_tipo);
    $stmt->bindParam(':disponivel', $novo_disponivel);
    $stmt->bindParam(':id', $id);
    //add novo_s campos caso necessario
    if ($stmt->execute()) {
        echo "quarto atualizado!!";
    } else {
        echo "erro ao atualizar quarto :(";
    }
}

