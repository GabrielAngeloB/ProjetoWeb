<?php session_start();  ?>
        <?php
        
        $nome_jogo = $_POST['nome_jogo'];
        $publisher = $_POST['publisher'];
        $dev = $_POST['dev'];
        $data_jogo = $_POST['data_lancamento'];
        $generos = array();
        $generos = $_POST['generos'];
        $desc_jogo = $_POST['desc_jogo'];
        $img_jogo = $_FILES['imagem'];
        require ('conecta.php');

        function adicionarJogo($nome_jogo, $publisher, $dev, $data_jogo, $generos, $desc_jogo, $img_jogo) {

            if (isset($nome_jogo) and isset($publisher) and isset($dev) and
                    isset($data_jogo) and !empty($generos) and
                    isset($desc_jogo)) {
                require ('conecta.php');
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

                        $nomeArquivo = $_FILES['imagem']['name'];
                        // Substituir espaços por sublinhado
                        $nomeArquivo = str_replace(' ', '_', $nomeArquivo);
                        $diretorio = "imagens/";
                        move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio . $nomeArquivo);

                        $linkImagem = $diretorio . $nomeArquivo;
                    } else {
                        echo "Erro no upload da imagem.";
                    }
                    date_default_timezone_set('America/Sao_Paulo');
                    $tempo = time();


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
        echo "<script>
                window.location.href = 'jogos_recentes.php';
                </script>";
        ?>
    </body>
</html>
<?php
$conecta->close();
?>