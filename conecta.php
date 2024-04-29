<?php

$nome_servidor = "localhost";
$nome_usuario = "root";
$senhabanco = "";
$banco = "db_review";
$conecta = new mysqli($nome_servidor, $nome_usuario, $senhabanco, $banco);
if ($conecta->connect_error) {
    die("Conexão falhou: " . $conecta->connect_error . "<br>");
}
?>
        
