<?php

session_start();
require('conecta.php');
$naotem = true;

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
$tenta_achar2 = "SELECT id_usuario, id_jogo FROM reviews WHERE id_usuario='$id_usuario' and id_jogo='$id_jogo'";
$resultado2 = $conecta->query($tenta_achar2);
if ($resultado2->num_rows > 0) {
    echo "<script> 
                alert('Você já possui 1 review neste jogo!');
                window.location.href = 'index.php';
            </script>";
    $naotem = false;
}

$review = $_POST['review'];
$notareview = $_POST['nota_review'];
if ($naotem) {
    date_default_timezone_set('America/Sao_Paulo');
    $tempo = time();

// You can optionally convert the timestamp to a human-readable format
    $horarioatual = date("Y-m-d H:i:s", $tempo);

    $sql = "INSERT INTO reviews (texto_review, id_jogo, id_usuario, horario_review) VALUES ('$review', '$id_jogo', '$id_usuario', '$horarioatual') ";
    if ($conecta->query($sql) === TRUE) {

        $ok = true;
    } else {
        $ok = false;
    }
    $sql = "SELECT MAX(id_review) as id_review FROM reviews WHERE id_usuario=$id_usuario";
    $resultado = $conecta->query($sql);
    if ($resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $id_review = &$linha['id_review'];
        }

        $sql = "INSERT INTO avaliacao(avaliacao_total, id_jogo, id_usuario, id_review) VALUES('$notareview', '$id_jogo', '$id_usuario', $id_review) ";
        if ($conecta->query($sql) === TRUE) {

            $ok = true;
        } else {
            $ok = false;
        }
    }


    $sql = "SELECT MAX(total_reviews) as total_reviews FROM reviews";
    $resultado = $conecta->query($sql);
    if ($resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $total_reviews = $linha['total_reviews'];
            $total_reviews += 1;
        }
    }
    $sql = "SELECT MAX(id_review) as id_review FROM reviews";
    $resultado = $conecta->query($sql);
    if ($resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $id_review1 = $linha['id_review'];
        }
        $sql = "UPDATE reviews SET total_reviews = $total_reviews WHERE id_review=$id_review1";
        if ($conecta->query($sql) === TRUE) {
            $ok = true;
        } else {
            $ok = false;
        }
    }



    $sql = "SELECT * FROM reviews WHERE id_usuario='$id_usuario'";
    $resultado = $conecta->query($sql);
    if ($resultado->num_rows > 0) {

        while ($linha = $resultado->fetch_assoc()) {
            $review123 = $resultado->num_rows;
        }
    } else {
        $review123 = 1;
        echo $review123;
    }
    $sql = "UPDATE usuario SET quant_reviews = '$review123' WHERE id_usuario = '$id_usuario'";
    if ($conecta->query($sql) === TRUE) {

        $ok = true;
    }
    for ($i = 0; $i < 1; $i++) {
        $sql = "SELECT AVG(avaliacao_total) as avaliacao_media FROM avaliacao WHERE id_jogo='$id_jogo'";
        $resultado = $conecta->query($sql);
        if ($resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                $avaliacao_total = $linha['avaliacao_media'];
            }
            $sql = "UPDATE games SET avaliacao_media=$avaliacao_total where id_jogo=$id_jogo ";
            if ($conecta->query($sql) === TRUE) {

                $ok = true;
            } else {
                $ok = false;
            }
        } else {
            $review123 = 1;
            echo $review123;
        }
        
        header('Location:jogo_mostrar.php?id_jogo1=' . $id_jogo);
    }
}
// Get the current timestamp using time() function





$conecta->close();
