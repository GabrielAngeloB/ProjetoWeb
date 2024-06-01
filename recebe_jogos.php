<?php
session_start();
?>

<?php

$nome_jogo = $_POST['nome_jogo'];
$publisher = $_POST['publisher'];
$dev = $_POST['dev'];
$data_jogo = $_POST['data_lancamento'];
$generos = $_POST['generos'];
$desc_jogo = $_POST['desc_jogo'];
$img_jogo = $_FILES['imagem'];
require ('conecta.php');
if ($_SESSION['login'] !== 'gabridestiny@hotmail.com') {
    echo "<script>
                window.location.href = 'index.php';
                </script>";
}

function adicionarJogo($nome_jogo, $publisher, $dev, $data_jogo, $generos, $desc_jogo, $img_jogo, $conecta) {

    if (isset($nome_jogo) && isset($publisher) && isset($dev) && isset($data_jogo) && !empty($generos) && isset($desc_jogo)) {
        $jogo_minusculo = strtolower($nome_jogo);
        $tenta_achar = "SELECT * FROM games WHERE LOWER(nome_jogo) = ?";
        
        $stmt = $conecta->prepare($tenta_achar);
        $stmt->bind_param("s", $jogo_minusculo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            echo "<script>
                alert('O jogo já está registrado');
                window.location.href = 'adicionar_jogos.php';
            </script>";
        } else {
            $generostotal = implode(", ", $generos);

            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                $tipoArquivo = $_FILES['imagem']['type'];
                if (!in_array($tipoArquivo, ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'])) {
                    echo "Erro: Tipo de arquivo inválido.";
                    exit;
                }

                $nomeArquivo = $_FILES['imagem']['name'];
                // Substituir espaços por sublinhado
                $nomeArquivo = str_replace(' ', '_', $nomeArquivo);
                $diretorio = "imagens/";
                move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio . $nomeArquivo);

                $linkImagem = $diretorio . $nomeArquivo;
            } else {
                echo "Erro no upload da imagem.";
                exit;
            }

            date_default_timezone_set('America/Sao_Paulo');
            $tempo = time();
            $horarioatual = date("Y-m-d H:i:s", $tempo);

            $sql = "INSERT INTO games (desenvolvedor, publisher, data_lancamento, nome_jogo, desc_jogo, img_jogo, generos, horario_postado, imagem_artwork)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conecta->prepare($sql);
            $stmt->bind_param("sssssssss", $dev, $publisher, $data_jogo, $nome_jogo, $desc_jogo, $linkImagem, $generostotal, $horarioatual, $linkImagem);
            
            if ($stmt->execute()) {
                echo "<script>
                    window.location.href = 'jogos_recentes.php';
                    </script>";
            } else {
                echo "Erro: " . $stmt->error;
            }
        }
    }
}

adicionarJogo($nome_jogo, $publisher, $dev, $data_jogo, $generos, $desc_jogo, $img_jogo, $conecta);
?>

<?php
$conecta->close();
?>
