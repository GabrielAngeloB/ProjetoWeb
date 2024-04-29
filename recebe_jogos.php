<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $nome_jogo = $_POST['nome_jogo'];
        $publisher = $_POST['publisher'];
        $dev = $_POST['dev'];
        $data_jogo = $_POST['data_lancamento'];
        $generos = array();
        $generos = $_POST['generos'];
        $desc_jogo = $_POST['desc_jogo'];
        $img_jogo = $_FILES['imagem'];
        $nome_servidor = "localhost";
        $nome_usuario = "root";
        $senhabanco = "";
        $banco = "db_review";

        function conectarBanco($nome_servidor, $nome_usuario, $senhabanco, $banco) {
            $conecta = new mysqli($nome_servidor, $nome_usuario, $senhabanco, $banco);
            if ($conecta->connect_error) {
                die("Conexão falhou: " . $conecta->connect_error . "<br>");
            }
        }

        conectarBanco($nome_servidor, $nome_usuario, $senhabanco, $banco);

        function adicionarJogo($nome_jogo, $publisher, $dev, $data_jogo, $generos, $desc_jogo, $img_jogo) {

            if (isset($nome_jogo) and isset($publisher) and isset($dev) and
                    isset($data_jogo) and !empty($generos) and
                    isset($desc_jogo)) {
                $nome_servidor = "localhost";
                $nome_usuario = "root";
                $senhabanco = "";
                $banco = "db_review";
                $conecta = new mysqli($nome_servidor, $nome_usuario, $senhabanco, $banco);
                if ($conecta->connect_error) {
                    die("Conexão falhou: " . $conecta->connect_error . "<br>");
                } else {
                    
                }
                $jogo_minusculo = strtolower($nome_jogo);
                $tenta_achar = "SELECT * FROM games WHERE LOWER(nome_jogo)='$jogo_minusculo'";
                $resultado = $conecta->query($tenta_achar);
                if ($resultado->num_rows > 0) {
                    echo "<script> 
                alert('O jogo já esta registrado');
                window.location.href = 'adicionar_jogos.php'
            </script>";
                } else {
                    $executado = false;
                    for ($i = 0;
                            $i < sizeof($generos);
                            $i++) {
                        if (!$executado) {
                            $generostotal = "$generos[$i]";
                            $executado = true;
                        } else {
                            $generostotal .= ", $generos[$i]";
                        }
                    }

                    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {


                        $tipoArquivo = $_FILES['imagem']['type'];
                        if (!in_array($tipoArquivo, ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'])) {
                            echo "Erro: Tipo de arquivo inválido.";
                            exit;
                        }


                        $nomeArquivo = basename($_FILES['imagem']['name']);
                        $diretorio = "imagens/";
                        move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio . $nomeArquivo);

                        session_start();
                        $linkImagem = $diretorio . $nomeArquivo;
                        $_SESSION['link'] = $linkImagem;
                    } else {
                        echo "Erro no upload da imagem.";
                    }
                    date_default_timezone_set('America/Sao_Paulo');
                    $tempo = time();

// You can optionally convert the timestamp to a human-readable format
                    $horarioatual = date("Y-m-d H:i:s", $tempo);

                    $sql = "INSERT INTO games (desenvolvedor, publisher, data_lancamento, nome_jogo, desc_jogo, img_jogo, generos, horario_postado)
                   VALUES ('$dev', '$publisher', '$data_jogo', '$nome_jogo', '$desc_jogo', '$linkImagem', '$generostotal', '$horarioatual')";
                    if ($conecta->query($sql) === TRUE) {
                        
                    } else {
                        echo die("Conexão falhou: " . $conecta->connect_error . "<br>");
                    }
                }
            }
        }

        adicionarJogo($nome_jogo, $publisher, $dev, $data_jogo, $generos, $desc_jogo, $img_jogo);
        header('Location:jogos_recentes.php');
        ?>
    </body>
</html>
<?php
$conecta->close();
?>