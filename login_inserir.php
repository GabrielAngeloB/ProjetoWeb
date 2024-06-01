<?php
session_start();

// Verifica se os campos 'nome' e 'senha' estão definidos
if (!isset($_POST['nome']) || !isset($_POST['senha'])) {
    echo "<script>
            window.location.href = 'login.php';
          </script>";
    exit;
}

$email = $_POST['nome'];
$senhalogin = $_POST['senha'];

require('conecta.php');

function verificarDados($email, $senhalogin) {
    require('conecta.php');
    $tenta_achar = "SELECT * FROM usuario WHERE email='$email' AND senha='" . md5($senhalogin) . "'";
    $resultado = $conecta->query($tenta_achar);
    
    if (isset($email) && isset($senhalogin)) {
        if ($resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                $_SESSION['id_usuario'] = $linha["id_usuario"];
            }
            $_SESSION['login'] = $email;
            $_SESSION['senha'] = $senhalogin;
            echo "<script>
                    window.location.href = 'index.php';
                  </script>";
        } else {
            $_SESSION['erro'] = true;
            echo "<script>
                    window.location.href = 'login.php';
                  </script>";
        }
    }
}

verificarDados($email, $senhalogin);

$conecta->close();
?>
