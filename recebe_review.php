<?php
session_start();
require('conecta.php');
$naotem = true;

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['id_jogo']) || !isset($_POST['review'])) {
    echo "<script>
            window.location.href = 'jogos_recentes.php';
          </script>";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_jogo = $_SESSION['id_jogo'];

$review = $_POST['review'];
$notareview = $_POST['nota_review'];

// Verifica se já existe um review para o jogo pelo usuário
$tenta_achar2 = $conecta->prepare("SELECT id_usuario, id_jogo FROM reviews WHERE id_usuario = ? AND id_jogo = ?");
$tenta_achar2->bind_param("ii", $id_usuario, $id_jogo);
$tenta_achar2->execute();
$resultado2 = $tenta_achar2->get_result();

if ($resultado2->num_rows > 0) {
    echo "<script> 
            alert('Você já possui 1 review neste jogo!');
            window.location.href = 'jogo_mostrar.php?id_jogo1=$id_jogo';
          </script>";
    $naotem = false;
}

if ($naotem) {
    date_default_timezone_set('America/Sao_Paulo');
    $horarioatual = date("Y-m-d H:i:s");

    // Inserir o review
    $sql = $conecta->prepare("INSERT INTO reviews (texto_review, id_jogo, id_usuario, horario_review) VALUES (?, ?, ?, ?)");
    $sql->bind_param("siis", $review, $id_jogo, $id_usuario, $horarioatual);
    $sql->execute();

    if ($sql->affected_rows > 0) {
        $id_review = $conecta->insert_id;

        // Inserir a avaliação
        $sql = $conecta->prepare("INSERT INTO avaliacao (avaliacao_total, id_jogo, id_usuario, id_review) VALUES (?, ?, ?, ?)");
        $sql->bind_param("diii", $notareview, $id_jogo, $id_usuario, $id_review);
        $sql->execute();

        // Atualizar a quantidade de reviews do usuário
        $sql = $conecta->prepare("UPDATE usuario SET quant_reviews = (SELECT COUNT(*) FROM reviews WHERE id_usuario = ?) WHERE id_usuario = ?");
        $sql->bind_param("ii", $id_usuario, $id_usuario);
        $sql->execute();

        // Atualizar a média de avaliações do jogo
        $sql = $conecta->prepare("UPDATE games SET avaliacao_media = (SELECT AVG(avaliacao_total) FROM avaliacao WHERE id_jogo = ?) WHERE id_jogo = ?");
        $sql->bind_param("ii", $id_jogo, $id_jogo);
        $sql->execute();

        echo "<script>
                window.location.href = 'jogo_mostrar.php?id_jogo1=$id_jogo';
              </script>";
    }
}

$conecta->close();
?>
