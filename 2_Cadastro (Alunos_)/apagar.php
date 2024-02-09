<?php

// Inclui a conexão com o banco de dados
include_once "conexao.php";

// Recebe o ID do registros
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

// Verifica se o ID não esta vazio
if (!empty($id)) {
    // Prepara a query paa deletar o usuário com o ID fornecido
    $query_usuario = "DELETE FROM usuarios WHERE id=:id";
    $del_usuario = $conn->prepare($query_usuario);
    $del_usuario->bindParam(':id', $id);

    // Executar a query para deletar o usuario
    if ($del_usuario->execute()) {
        // Se o usuario for deletado com sucesso, preparar a query para deletar o endereço associado a essen susario
        $query_endereco = "DELETE FROM enderecos WHERE usuario_id=:usuario_id";
        $del_endereco = $conn->prepare($query_endereco);
        $del_endereco->bindParam(':usuario_id', $id);

        // Executa a query para deletar o endereço associado ao usuario
        if($del_endereco->execute()){
            // Se ambos, usuário e endereço, forem deletados com sucesso, retornar uma mensagem de sucesso
            $retorna = ['status' => true, 'msg' => "<div class='alert alert-sucess' role='alert'>Usuário apagado com sucesso!</div>"];
        }else{
            // Se o usuário for deletado com sucesso, mas o endereço não, retornar uma mensagem de erro
            $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Usuário apagado, endereço não apagado com sucesso!</div>"];
        }
    } else {
        // Se houver um erro ao deletar o usuário, retornar uma mensagem de erro
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Usuário apagado, endereço não apagado com sucesso!</div>"];
    }
} else {
    // Se o ID estiver vazio, retornar uma mensagem de erro
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado!</div>"];
}

// Retornar o resultado em formato JSON
echo json_encode($retorna);