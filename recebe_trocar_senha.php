<?php
session_start();
require('conecta.php');
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
}

$senha_atual = $_POST['current_password'];
$senha_nova = $_POST['new_password'];
$confirmar_senha_nova = $_POST['confirm_new_password'];
if($senha_nova == $confirmar_senha_nova) {
    $ok = true;
}else {
    $ok = false;
    echo "<script>
                alert('Alguma das senhas não coincidem!');
                window.location.href = 'trocar_senha.php';
                </script>";
}
if (strlen($senha_atual) < 8 && strlen($senha_nova)< 8 && strlen($confirmar_senha_nova)) {
    $ok = false;
    echo "<script>
                alert('Mínimo de 8 caracteres!');
                window.location.href = 'trocar_senha.php';
                </script>";
}else {
    
}
if($ok) {
$sql = "SELECT * from usuario WHERE id_usuario=$id_usuario and senha='".md5($senha_atual)."'";
$resultado = $conecta->query($sql);
if ($resultado->num_rows > 0) {
    $sql2 = "UPDATE usuario SET senha='".md5($senha_nova)."' WHERE id_usuario = $id_usuario";
    $resultado = $conecta->query($sql2);
    header('Location:editar_usuario.php');
}else {
    echo "<script>
                alert('Alguma das senhas não coincidem!');
                window.location.href = 'trocar_senha.php';
                </script>";
}
}

