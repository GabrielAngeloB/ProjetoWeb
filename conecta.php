<?php

$nome_servidor = "sql10.freemysqlhosting.net";
$nome_usuario = "sql10709781";
$senhabanco = "41AiLqmDCg";
$banco = "sql10709781";

$conecta = new mysqli($nome_servidor, $nome_usuario, $senhabanco, $banco);
if ($conecta->connect_error) {
    die("Conexão falhou: " . $conecta->connect_error . "<br>");
}
mysqli_set_charset($conecta, "utf8mb4");
?>
        
