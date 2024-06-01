<?php
session_start();
require('conecta.php');

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha_atual = $_POST['current_password'];
    $senha_nova = $_POST['new_password'];
    $confirmar_senha_nova = $_POST['confirm_new_password'];
    
     

    if (strlen($senha_atual) < 8 || strlen($senha_nova) < 8 || strlen($confirmar_senha_nova) < 8) {
        echo "<script>
                alert('Mínimo de 8 caracteres!');
                window.location.href = 'trocar_senha.php';
              </script>";
        exit();
    }

    if ($senha_nova !== $confirmar_senha_nova) {
        echo "<script>
                alert('As novas senhas não coincidem!');
                window.location.href = 'trocar_senha.php';
              </script>";
        exit();
    }

    $sql = "SELECT * FROM usuario WHERE id_usuario = $id_usuario AND senha = '".md5($senha_atual)."'";
    $resultado = $conecta->query($sql);

    if ($resultado->num_rows > 0) {
        $senha_nova_hashed = md5($senha_nova);
        $sql2 = "UPDATE usuario SET senha = '$senha_nova_hashed' WHERE id_usuario = $id_usuario";
        if ($conecta->query($sql2) === TRUE) {
            echo "<script>
                    alert('Senha atualizada com sucesso!');
                    window.location.href = 'editar_usuario.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Erro ao atualizar a senha.');
                    window.location.href = 'trocar_senha.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Senha atual incorreta.');
                window.location.href = 'trocar_senha.php';
              </script>";
    }
}
?>
