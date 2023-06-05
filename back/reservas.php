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
    $stmt = $conn->prepare("SELECT * FROM reservas");
    //aqui eu executo o select
    $stmt->execute();
    //aqui eu recebo os dados do banco por meio do PDO
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //transformo os dados da variave $reservas em um JSON valido
    echo json_encode($reservas);
}

//Rota para criar reservas
//Rota para inserir reservas
//Utilizando o POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_cliente = $_POST['nome_cliente'];
    $numero = $_POST['numero'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    //inserir outros campos caso necessario ....

    $stmt = $conn->prepare("INSERT INTO reservas (nome_cliente, numero, check_in, check_out) VALUES (:nome_cliente, :numero, :check_in, :check_out)");
    $stmt->bindParam(':nome_cliente', $nome_cliente);
    $stmt->bindParam(':numero', $numero);
    $stmt->bindParam(':check_in', $check_in);
    $stmt->bindParam(':check_out', $check_out);
    //Outros bindParams ....

    if ($stmt->execute()) {
        echo "reserva criado com sucesso!!";
    } else {
        echo "Erro ao criar reserva";
    }
}
// Rota para excluir um reserva

if($_SERVER['REQUEST_METHOD']==='DELETE' && isset($_GET['id'])){
    $id   = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM reservas WHERE id = :id");
    $stmt-> bindParam(':id', $id);

    if($stmt->execute()){
        echo "reserva excluido com sucesso!!!";
    } else {
        echo "Erro ao excluir reserva!";
    }
}

//Rota para atualizar um reserva existente
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    parse_str(file_get_contents("php://input"), $_PUT);
    $id = $_GET['id'];
    $novo_nome_cliente = $_PUT['nome_cliente'];
    $novo_numero = $_PUT['numero'];
    $novo_check_in = $_PUT['check_in'];
    $novo_check_out = $_PUT['check_out'];
    //add novo campos caso necessario
    $stmt = $conn->prepare("UPDATE reservas SET nome_cliente = :nome_cliente, numero = :numero, check_in = :check_in, check_out = :check_out WHERE id = :id");
    $stmt->bindParam(':nome_cliente', $novo_nome_cliente);
    $stmt->bindParam(':numero', $novo_numero);
    $stmt->bindParam(':check_in', $novo_check_in);
    $stmt->bindParam(':check_out', $novo_check_out);
    $stmt->bindParam(':id', $id);
    //add novo campos caso necessario
    if ($stmt->execute()) {
        echo "reserva atualizado!!";
    } else {
        echo "erro ao atualizar reserva :(";
    }
}

