<?php

session_start();
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    session_unset();
    echo "<link href='css2/estilos.css' type='text/css' rel='stylesheet'> "
    . "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js'></script>"
    . "<link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css' rel='stylesheet'>"; // Adiciona o link para o CSS customizado
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                // Altera o background da página
                document.body.style.backgroundColor = '#37363d';
                
                Swal.fire({
                    title: 'Erro!',
                    text: 'Esta página só pode ser acessada por usuário logado!',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'custom-swal-popup'
                    },
                    allowOutsideClick: false, // Evita fechar ao clicar fora do alerta
                    timer: 3000,
                    timerProgressBar: true
                }).then((result) => {
                    window.location.href = 'login.php';
                });

                // Caso o SweetAlert2 seja fechado pelo temporizador, redirecionar para a página de login
                Swal.getTimerLeft();
                const timerInterval = setInterval(() => {
                    if (Swal.getTimerLeft() <= 0) {
                        clearInterval(timerInterval);
                        window.location.href = 'login.php';
                    }
                }, 100);
            });
          </script>";
    exit; // Certifique-se de parar a execução do script após redirecionar
}

$adicionar = '';
if ($_SESSION['login'] == 'gabridestiny@hotmail.com') {
    $adicionar = "<a class='dropdown-item' href='adicionar_jogos.php'>Adicionar Jogo</a>";
}
$logado = $_SESSION['login'];

require('conecta.php');

$limit = 20;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $limit;

$stmt = $conecta->prepare("SELECT SQL_CALC_FOUND_ROWS img_jogo, generos, desc_jogo, nome_jogo, id_jogo, avaliacao_media, horario_postado 
                           FROM games 
                           ORDER BY id_jogo DESC 
                           LIMIT ?, ?");
$stmt->bind_param("ii", $inicio, $limit);
$stmt->execute();
$resultado = $stmt->get_result();

$total_jogos = $conecta->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];
$total_paginas = ceil($total_jogos / $limit);
if ($pagina > $total_paginas) {
     echo "<script>
                window.location.href = 'pagina_nao_encontrada.php';
                </script>";
    exit; // Para garantir que o script seja encerrado após o redirecionamento
}

$link = $generos = $desc = $nomejogo = $id_jogo = $media = $mensagem = [];
if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $link[] = $linha["img_jogo"];
        $generos[] = $linha["generos"];
        $desc[] = $linha["desc_jogo"];
        $nomejogo[] = $linha["nome_jogo"];
        $id_jogo[] = $linha["id_jogo"];
        $media[] = $linha['avaliacao_media'];

        $postedTimeUnix = strtotime($linha['horario_postado']);
        $currentTimeUnix = time();
        $timeDiffSeconds = $currentTimeUnix - $postedTimeUnix;

        $minutos = floor($timeDiffSeconds / 60);
        $horas = floor($minutos / 60);
        $dias = floor($horas / 24);

        if ($minutos < 1) {
            $mensagem[] = "agora!";
        } else if ($minutos < 60) {
            $mensagem[] = "$minutos minuto(s) atrás!";
        } else if ($horas < 24) {
            $mensagem[] = "$horas hora(s) atrás!";
        } else {
            $mensagem[] = "$dias dia(s) atrás!";
        }
    }
}
$stmt->close();

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
}

$stmt = $conecta->prepare("SELECT img_perfil FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $img_perfil = $linha['img_perfil'];
    }
}
$stmt->close();
?>
<html>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css2/estilos.css">
    <title>Jogos Recentes</title>
    <link rel="icon" href="https://static.thenounproject.com/png/122214-200.png">
    <head>
        

    </script>

</head>
<body style="background-color:#242629">


   <nav class="navbar navbar-expand-sm" style="background-color:darkslategrey; z-index:2;">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="float:left">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse flex-grow-0" id="navbarNav">
                    <ul class="navbar-nav me-auto">  <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php" style="color:white; font-size:26px; padding-right:10px; font-weight:bold;">Inicio</a>
                        </li>
                        <li class="nav-item dropdown" style="font-size:26px; font-weight:bold;">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span style="color:white;">Jogos</span>

                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="padding-right:10px;">
                                <li><a class="dropdown-item" href="jogos_recentes.php" ">Jogos recentes</a></li>
                                <li><a class="dropdown-item" href="melhores_review.php">Melhores Avaliados</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" style="color:white; font-size:26px; padding-right:10px; font-weight:bold;" href="reviews_usuario.php">Reviews</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" style="color:white; font-size:26px; padding-right:10px; font-weight:bold;" href="lista_generos.php">Generos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" style="color:white; font-size:26px; font-weight:bold;" href="lista_jogos.php">Lista</a>
                        </li>
                    </ul>
                </div>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="thumbnail" src="<?php echo $img_perfil; ?>" style="width:50px; height:50px; text-align:right; border-radius:50%; margin-right:7px; border: 2px solid black;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end position-absolute" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="pagina_usuario.php?id_usuario=<?php echo $id_usuario; ?>">Ver perfil</a>
                            <a class="dropdown-item" href="editar_usuario.php">Editar perfil</a>
                            <?php echo $adicionar ?>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>


            </div>
        </nav>
    <h1 class="mx-auto letra" style="color:white; margin-top:100px; text-align:center; "><span style="background-color:#343434; padding-left:13px; padding-right:13px; border-radius:10px; text-shadow: 3px 3px black; font-family:monospace;  "> ⚡︎ Mais Recentes ⚡︎</span></h1> 
<?php
for ($i = 0; $i < sizeOf($id_jogo); $i++) {
    ($i % 2 == 0) ? ($fade = "fadeInFromRight") : ($fade = "fadeInFromLeft");
    ($i % 2 == 0) ? ($resp = "responsivo") : ($resp = "responsivo2");
    if ($i == 0) {
        $jogo_recente[$i] = "
    <div class='card mb-3 mx-auto $resp $fade' style='margin-top:40px;'>
        <div class='row g-0'>
            <div class='col-md-4'>
            <a href='jogo_mostrar.php?id_jogo1=$id_jogo[$i]'>"
                . "<img src='$link[$i]' loading='lazy' class='img-fluid imagem1' style='width:100%; height:100%; max-height:220px; object-fit: fill;'alt='...'>
                </a>
            </div>
            <div class='col-md-8 d-flex'>
                <div class='card-body' style='background-color:#9B9CA6; max-height:220px; overflow:auto;'>
                    <h5 class='card-title container-fluid' style='font-weight:bold; font-size:23px; text-align:center; text-decoration:underline;'>$nomejogo[$i]</h5>
                    <p class='card-text container-fluid' style='flex-grow: 1;object-fit:fill; text-align:justify;'>$desc[$i]<span style='font-weight:bold'><br>Generos</span>: $generos[$i].<br> <span style='font-weight:bold'>Postado</span>: $mensagem[$i]
                    <p class='rating-box mx-auto' style='display:flex; justify-content:center; font-size:20px; max-width:180px; border:2px solid gainsboro;  background-color:black;'>Nota média:<span class='mx-auto' style='text-decoration: underline; color:white; display:flex; justify-content:center; font-size:20px; background-color:black; padding-left:3px; padding-right:3px; border-radius:20%;'>$media[$i]</p></h5>
                    <p style='display:flex; justify-content:right; overflow:auto;' class='card-text container-fluid'><small class='text-body-secondary' style='display:flex; justify-content:flex-end'>
                            <form action='jogo_mostrar.php' method='get'>
                                <input type='hidden' name='id_jogo1' value='$id_jogo[$i]'>
                                <button type='submit' class='btn btn-primary mx-auto vermais' style='font-weight:bold; font-style:italic;  text-shadow: 2px 2px #000; font-size:18px; margin:-13px; background-color:darkslategrey; border:1px solid darkslategrey;'>Ver detalhes</button>
                            </form></small></p>
                </div>
            </div>
        </div>
    </div>";
    } else {
        $jogo_recente[$i] = "
<div class='card mb-3 mx-auto $resp $fade' style='margin-top:40px;'>
    <div class='row g-0'>
    <a href='jogo_mostrar.php?id_jogo1=$id_jogo[$i]'>
        <div class='col-md-4'><img src='$link[$i]' loading='lazy' class='img-fluid imagem1' style='width:100%; height:100%; max-height:220px; object-fit: fill;'alt='...'>
            </a>
        </div>
        <div class='col-md-8 d-flex'>
            <div class='card-body' style='background-color:#9B9CA6; max-height:220px; overflow:auto;'>
                <h5 class='card-title container-fluid' style='font-weight:bold; font-size:23px; text-align:center; text-decoration:underline;'>$nomejogo[$i]</h5>
                <p class='card-text container-fluid' style='flex-grow: 1;object-fit:fill; text-align:justify;'>$desc[$i]<span style='font-weight:bold'><br>Generos</span>: $generos[$i].<br> <span style='font-weight:bold'>Postado</span>: $mensagem[$i]
                <p class='rating-box mx-auto' style='display:flex; max-width:180px; justify-content:center; font-size:20px; border:2px solid gainsboro;  background-color:black;'>Nota média:<span class='mx-auto' style='text-decoration: underline; color:white; display:flex; justify-content:center; font-size:20px; background-color:black; padding-left:3px; padding-right:3px; border-radius:20%;'>$media[$i]</p></h5>
                <p style='display:flex; justify-content:right; overflow:auto;' class='card-text container-fluid'><small class='text-body-secondary' style='display:flex; justify-content:flex-end'>
                        <form action='jogo_mostrar.php' method='get'>
                            <input type='hidden' name='id_jogo1' value='$id_jogo[$i]'>
                            <button type='submit' class='btn btn-primary mx-auto vermais' style='font-weight:bold; font-style:italic;  text-shadow: 2px 2px #000; font-size:18px; margin:-13px; background-color:darkslategrey; border:1px solid darkslategrey;'>Ver detalhes</button>
                        </form></small></p>
            </div>
        </div>
    </div>
</div>";
    }
    echo $jogo_recente[$i];
}



?>
    <div style="text-align:center;">
    <ul class="pagination justify-content-center">
        <?php
        $max_links = 10; // Número máximo de links visíveis

        // Calcula o início e o fim da faixa de páginas a serem exibidas
        $start = max(1, $pagina - floor($max_links / 2));
        $end = min($total_paginas, $start + $max_links - 1);

        // Se o número máximo de links excede o total de páginas, ajusta a faixa
        if ($end - $start + 1 < $max_links) {
            $start = max(1, $end - $max_links + 1);
        }

        // Exibe o link "Anterior" se aplicável
        if ($pagina > 1) {
            echo '<li class="page-item style="height:40px;"><a class="custom-page-link2" style="color: white; bottom:1%;" href="?pagina=' . ($pagina - 1) . '">&laquo;</a></li>';
        }

        // Exibe os números das páginas
        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $pagina) ? 'active' : '';
            $background = ($i == $pagina) ? '#343a40' : '#f8f9fa'; // cinza claro para não ativo
            echo '<li class="page-item"><a class="page-link ' . $active . '" style="font-size:30px; border: 2px solid black; color:black; border-radius:20px; margin-left:5px; padding-right:3px; padding-left:3px; background-color: ' . $background . ';" href="?pagina=' . $i . '">' . $i . '</a></li>';
        }

        // Exibe o link "Próxima" se aplicável
        if ($pagina < $total_paginas) {
            echo '<li class="page-item style="height:40px;"><a class="custom-page-link2" style="color: white; bottom:1%;" href="?pagina=' . ($pagina + 1) . '">&raquo;</a></li>';
        }
        ?>
    </ul>
</div>
    

    <br>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>    <script src="javascriptsite.js"></script> 
</body>
</html>
<?php
$conecta->close();
?>