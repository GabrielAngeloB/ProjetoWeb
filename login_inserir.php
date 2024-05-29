<?php
session_start();
$email = $_POST['nome'];
$senhalogin = $_POST['senha'];
$nome = $_POST['nome'];
$senha = $_POST['senha'];
require ('conecta.php');

function verificarDados($email, $senhalogin) {
    require ('conecta.php');
    $tenta_achar = "SELECT * FROM usuario WHERE email='$email' AND senha='" . md5($senhalogin) . "'";
    $resultado = $conecta->query($tenta_achar);
    if (isset($email) and isset($senhalogin)) {
        if ($resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                $_SESSION['id_usuario'] = $linha["id_usuario"];
            }
            $_SESSION['login'] = $email;
            $_SESSION['senha'] = $senhalogin;
            echo "<script> 
                window.location.href = 'index.php';
            </script>";
            $erro = false;
        } else {
            $erro = true;
            $_SESSION['erro'] = $erro;
            echo "<script> 
                window.location.href = 'login.php';
            </script>";
        }
    }
}

verificarDados($email, $senhalogin);
?>
<?php

$conecta->close();
?>