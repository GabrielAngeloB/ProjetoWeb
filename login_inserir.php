
<?php

session_start();

$email = $_POST['nome'];
$senhalogin = $_POST['senha'];
$nome = $_POST['nome'];
$senha = $_POST['senha'];
$nome_servidor = "localhost";
$nome_usuario = "root";
$senhabanco = "";
$banco = "db_review";
$conecta = new mysqli($nome_servidor, $nome_usuario, $senhabanco, $banco);
if ($conecta->connect_error) {
    die("Conexão falhou: " . $conecta->connect_error . "<br>");
} else {
    
}

function verificarDados($email, $senhalogin) {
    $nome_servidor = "localhost";
    $nome_usuario = "root";
    $senhabanco = "";
    $banco = "db_review";
    $conecta = new mysqli($nome_servidor, $nome_usuario, $senhabanco, $banco);
    if ($conecta->connect_error) {
        die("Conexão falhou: " . $conecta->connect_error . "<br>");
    } else {
        
    }
    $tenta_achar = "SELECT * FROM usuario WHERE email='$email' AND senha='" . md5($senhalogin) . "'";
    $resultado = $conecta->query($tenta_achar);
    if (isset($email) and isset($senhalogin)) {
        if ($resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                $_SESSION['id_usuario'] = $linha["id_usuario"];
            }
            $_SESSION['login'] = $email;
            $_SESSION['senha'] = $senhalogin;
            header('location:index.php');
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