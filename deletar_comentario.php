<?php

session_start();
$nome_usuario1 = array();

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    session_unset();
    echo "<script>
                alert('Esta página só pode ser acessada por usuário logado');
                window.location.href = 'login.php';
                </script>";
}
require('conecta.php');
$logado = $_SESSION['login'];
$id_review = $_POST['id_review'];
if (isset($_POST['delete'])) {
    $conf = true;
} else {
    header("Location:jogos_recentes.php");
}
if ($conf) {
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
    } else {
        echo "Session variable not found.";
    }
    if (isset($_SESSION['id_jogo'])) {
        $id_jogo = $_SESSION['id_jogo'];
    } else {
        echo "Session variable not found.";
    }
    $sql = "DELETE FROM reviews WHERE id_review=$id_review";
    if ($conecta->query($sql) === TRUE) {
        
    } else {
        echo "Erro ao apagar o registro: " . $conecta->error . "<br>";
    }
    $sql = "DELETE FROM avaliacao WHERE id_review=$id_review";
    if ($conecta->query($sql) === TRUE) {
        
    } else {
        echo "Erro ao apagar o registro: " . $conecta->error . "<br>";
    }
    $sql = "SELECT MAX(quant_reviews) as quant_reviews FROM usuario WHERE id_usuario = $id_usuario";
    $resultado = $conecta->query($sql);
    if ($resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $quant_reviews = $linha['quant_reviews'];
            $quant_reviews -= 1;
            $sql = "UPDATE usuario SET quant_reviews = $quant_reviews";
            if ($conecta->query($sql) === TRUE) {

                $ok = true;
            }
        }
    }
    $sql = "SELECT * FROM reviews WHERE id_usuario='$id_usuario'";
    $resultado = $conecta->query($sql);
    if ($resultado->num_rows > 0) {

        while ($linha = $resultado->fetch_assoc()) {
            $review123 = $resultado->num_rows;
        }
    } else {
        $review123 = 0;
    }
    $sql = "UPDATE usuario SET quant_reviews = '$review123' WHERE id_usuario = '$id_usuario'";
    if ($conecta->query($sql) === TRUE) {

        $ok = true;
    }
    if (isset($_POST['jogo_excluir'])) {
        $id_jogo = $_POST['jogo_excluir'];
        $sql2 = "SELECT AVG(avaliacao_total) as avaliacao_media FROM avaliacao WHERE id_jogo = '$id_jogo'";
    $resultado = $conecta->query($sql2);
    while ($row = $resultado->fetch_assoc()) {
        $avaliacao_media = (int) $row['avaliacao_media'];
    }
    $sql = "UPDATE games SET avaliacao_media= '$avaliacao_media' WHERE id_jogo=$id_jogo";
    if ($conecta->query($sql) === TRUE) {
        $ok = true;
    }
    }


    $sql2 = "SELECT AVG(avaliacao_total) as avaliacao_media FROM avaliacao WHERE id_jogo = '$id_jogo'";
    $resultado = $conecta->query($sql2);
    while ($row = $resultado->fetch_assoc()) {
        $avaliacao_media = (int) $row['avaliacao_media'];
    }
    $sql = "UPDATE games SET avaliacao_media= '$avaliacao_media' WHERE id_jogo=$id_jogo";
    if ($conecta->query($sql) === TRUE) {
        $ok = true;
    }
        

    header('Location:jogo_mostrar.php?id_jogo1=' . $id_jogo);
}

$conecta->close();
