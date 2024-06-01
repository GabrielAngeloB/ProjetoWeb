<?php
session_start();

// Verifica se os dados do formulário foram submetidos
if (!isset($_POST['nomecad']) || !isset($_POST['emailcad']) || !isset($_POST['senhacad'])) {
    echo "<script>window.location.href = 'cadastro.php';</script>";
    exit();
}

$nomecad = $_POST['nomecad'];
$senhacad = $_POST['senhacad'];
$emailcad = $_POST['emailcad'];
require ('conecta.php');

function inserirDados($nomecad, $emailcad, $senhacad) {
    $ok = true;
    $erro1 = false;
    require ('conecta.php');

    $tenta_achar = "SELECT * FROM usuario WHERE email='$emailcad' OR nome_usuario='$nomecad'";
    $resultado = $conecta->query($tenta_achar);
    
    if ($resultado->num_rows > 0) {
        $erro1 = true;
        $_SESSION['erro1'] = true;
        echo "<script>window.location.href = 'cadastro.php';</script>";
        $ok = false;
    } else {
        if (!filter_var($emailcad, FILTER_VALIDATE_EMAIL)) {
            $ok = false;
        }
        if ((!isset($nomecad) || empty($nomecad))) {
            echo "<script>window.location.href = 'cadastro.php';</script>";
            $ok = false;
        }
        if (!isset($emailcad) || empty($emailcad)) {
            $ok = false;
        }
        if (!isset($senhacad) || empty($senhacad) || strlen($senhacad) < 8) {
            echo "<script>window.location.href = 'cadastro.php';</script>";
            $ok = false;
        }

        if ($ok) {
            date_default_timezone_set('America/Sao_Paulo');
            $tempo = time();
            $horarioatual = date("Y-m-d H:i:s", $tempo);

            $sql = "INSERT INTO usuario (nome_usuario, email, senha, horario_criacao)
                    VALUES ('$nomecad', '$emailcad', '" . md5($senhacad) . "', '$horarioatual')";
            
            if ($conecta->query($sql) === TRUE) {
                echo "<script>window.location.href = 'login.php';</script>";
                $ok = true;
            } else {
                $ok = false;
            }
        }
    }
    $conecta->close();
}

inserirDados($nomecad, $emailcad, $senhacad);
?>
