<?php

session_start();

if ((!isset($_SESSION['login']) || !isset($_SESSION['senha']))) {
    session_unset();
    echo "<script>
                alert('Esta página só pode ser acessada por usuário logado');
                window.location.href = 'login.php';
                </script>";
    exit; 
}

require('conecta.php');

$logado = $_SESSION['login'];
$id_review = $_POST['id_review'];

if (isset($_POST['delete'])) {
    $conf = true;
} else {
    header("Location: jogos_recentes.php");
    exit; 
}

if ($conf) {
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
    } else {
        echo "Session variable not found.";
        exit; 
    }
    if (isset($_POST['validar'])) {
        $id_jogo=$_POST['jogo_excluir'];
    } else {
        if (isset($_SESSION['id_jogo'])) {
            $id_jogo = $_SESSION['id_jogo'];
        } else {
            
        }
    }

    
    $sql_delete_review = "DELETE FROM reviews WHERE id_review=$id_review";
    if (!$conecta->query($sql_delete_review)) {
        echo "Erro ao apagar o registro: " . $conecta->error . "<br>";
    }

    
    $sql_delete_avaliacao = "DELETE FROM avaliacao WHERE id_review=$id_review";
    if (!$conecta->query($sql_delete_avaliacao)) {
        echo "Erro ao apagar o registro: " . $conecta->error . "<br>";
    }

    
    $sql_count_reviews = "SELECT COUNT(*) AS num_reviews FROM reviews WHERE id_usuario='$id_usuario'";
    $resultado_count_reviews = $conecta->query($sql_count_reviews);
    if ($resultado_count_reviews->num_rows > 0) {
        $linha_count_reviews = $resultado_count_reviews->fetch_assoc();
        $quant_reviews = $linha_count_reviews['num_reviews'];
    } else {
        $quant_reviews = 0;
    }

    
    $sql_update_quant_reviews = "UPDATE usuario SET quant_reviews = '$quant_reviews' WHERE id_usuario = '$id_usuario'";
    if (!$conecta->query($sql_update_quant_reviews)) {
        echo "Erro ao atualizar a quantidade de reviews do usuário: " . $conecta->error;
    }
    $sql_avaliacao_media = "SELECT AVG(avaliacao_total) as avaliacao_media FROM avaliacao WHERE id_jogo = '$id_jogo'";
    $resultado_avaliacao_media = $conecta->query($sql_avaliacao_media);
    if ($resultado_avaliacao_media->num_rows > 0) {
        $row_avaliacao_media = $resultado_avaliacao_media->fetch_assoc();
        $avaliacao_media = (int) $row_avaliacao_media['avaliacao_media'];

        
        $sql_update_avaliacao_media = "UPDATE games SET avaliacao_media= '$avaliacao_media' WHERE id_jogo=$id_jogo";
        if (!$conecta->query($sql_update_avaliacao_media)) {
            echo "Erro ao atualizar a avaliação média do jogo: " . $conecta->error . "<br>";
        }
    }

    
    header('Location: jogo_mostrar.php?id_jogo1=' . $id_jogo);
    exit; 
    unset($_POST['validar']);
}


$conecta->close();
?>
