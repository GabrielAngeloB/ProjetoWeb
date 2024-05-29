<?php
session_start();
if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    session_unset();
    echo "<script>
        alert('Esta página só pode ser acessada por usuário logado');
        window.location.href = 'login.php';
        </script>";
}
$logado = $_SESSION['login'];
$adicionar = '';
if ($_SESSION['login'] == 'gabridestiny@hotmail.com') {
    $adicionar = "<a class='dropdown-item' href='adicionar_jogos.php'>Adicionar Jogo</a>";
}
require('conecta.php');
if (isset($_GET['id_usuario']) && isset($_SESSION['id_usuario'])) {
if (isset($_GET['id_usuario'])) {
    $id_usuario2 = $_SESSION['id_usuario'];
    $id_usuario = $_GET['id_usuario'];
    // Verifica se o ID do usuário existe no banco de dados
    $sql = "SELECT * FROM usuario WHERE id_usuario = $id_usuario";
    $resultado = $conecta->query($sql);
    if ($resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $username = $linha['nome_usuario'];
            $email = $linha['email'];
            $img_perfil = $linha['img_perfil'];
            $quant_reviews = $linha['quant_reviews'];
            $horario = $linha['horario_criacao'];
            $data_formatada = date('d/m/Y', strtotime($horario));
        }
        $sql2 = "SELECT horario_criacao FROM usuario WHERE id_usuario = '$id_usuario'";
            $result = $conecta->query($sql2);
            if ($result->num_rows > 0) {
                date_default_timezone_set('America/Sao_Paulo');
                $row = $result->fetch_assoc();
                $postedTimeUnix = strtotime($row['horario_criacao']);
            } else {
                
            }

            // 2. Get current timestamp
            $currentTimeUnix = time();

            // 3. Calculate time difference in seconds
            $timeDiffSeconds = $currentTimeUnix - $postedTimeUnix;

            // 4. Convert time difference to appropriate units (seconds, minutes, hours, days)
            $minutos = floor($timeDiffSeconds / 60);
            $horas = floor($minutos / 60);
            $dias = floor($horas / 24);

            // 5. Construct the time-ago message
            if ($minutos < 1) {
                $mensagem = "agora!";
            } else if ($minutos < 60) {
                $mensagem = "$minutos minuto(s) atrás!";
            } else if ($horas < 24) {
                $mensagem = "$horas hora(s) atrás!";
            } else {
                $mensagem = "$dias dia(s) atrás!";
            }
    } else {
        // Se o ID do usuário não existir, redireciona para outra página ou exibe uma mensagem de erro
        echo "<script> window.location.href = 'pagina_nao_encontrada.php';</script>";
        exit; // Finaliza o script para evitar a exibição do restante do conteúdo
    }
}else {
}
$sql = "SELECT * FROM usuario WHERE id_usuario = $id_usuario2";
    $resultado = $conecta->query($sql);
    if ($resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $img_perfil2 = $linha['img_perfil'];

        }
    }
}else {
    echo "<script>
                window.location.href = 'pagina_nao_encontrada.php';
                </script>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil do Usuário</title>
        <link rel="icon" href="https://static.thenounproject.com/png/122214-200.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css2/estilos.css">
        <style>
            body {
                background-color: #242629;
            }
            .container {
                margin-top: 85px;
                margin-bottom: 40px;
            }
            .card {
                border: 1px solid black;
            }
            .card-body {
                border: 3px solid black;
                border-radius: 0.5%;
                background-color: #484848;
            }
            .card-inner {
                background-color: #909090;
                border: 3px solid black;
                border-radius: 0.5%;
            }
            .thumbnail img {
                width: 200px;
                height: 200px;
                border-radius: 50%;
            }
        </style>
    </head>
    <body>

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
                            <img class="thumbnail" src="<?php echo $img_perfil2; ?>" style="width:50px; height:50px; text-align:right; border-radius:50%; margin-right:7px; border: 2px solid black;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end position-absolute" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="pagina_usuario.php?id_usuario=<?php echo $id_usuario2; ?>">Ver perfil</a>
                            <a class="dropdown-item" href="editar_usuario.php">Editar perfil</a>
                            <?php echo $adicionar ?>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>


            </div>
        </nav>

        <div class="container tamanhocard fadeInFromBottom">
            <h1 class="mb-4"></h1>
            <?php if (isset($id_usuario)): ?> <!-- Verifica se o ID do usuário está definido -->
                <div class="card no-hover-effect">
                    <div class="card-body" style="background-color: #989898">
                        <div class="container mt-2" style="width:100%;">
                            <h1 class="mb-4 letrafoda" style="text-align:center; font-weight:bold; text-shadow: -1px 0 darkslategrey, 0 1px darkslategrey, 1px 0 darkslategrey, 0 -1px darkslategrey;">
                                <span style="border:2px solid black; padding-right:9px; padding-left:9px; border-radius:10px; background-color:black; margin:auto; font-family:monospace; color:white">Perfil do Usuário</span>
                            </h1>
                            <div class="card mb-4">
                                <div class="card-body card-inner cartel" style="text-align:center; background-color:#D0D0D0 ">
                                    <div class="thumbnail imagem3">
                                        <img style="border: 2px solid black;" src="<?php echo $img_perfil ?>" id="profile_pic_preview" class="img-fluid mb-3 d-flex imagem3 " alt="Profile Picture">
                                    </div>
                                    <div class="form-group" style="margin-top:15px;">
                                        <label for="username" style="font-weight:bold; font-family: 'Arial Black', Gadget, sans-serif;">Nickname:</label>
                                        <p>"<span style="font-style:italic; top:5px; font-size:17px; text-decoration:underline;" ><?php echo $username; ?></span>"</p></span>
                                    </div>
                                    <hr style="position:relative; bottom:5px;" class="rounded">
                                    <div class="form-group" style="position:relative; bottom:8px;">
                                        <label for="email" style="font-weight:bold; font-family: 'Arial Black', Gadget, sans-serif;">Email:</label>
                                        <p>"<span style="font-style:italic; top:5px; font-size:17px; text-decoration:underline;" ><?php echo $email; ?></span>"</p></span>
                                        <hr style="position:relative; bottom:5px;" class="rounded">
                                        <div class="form-group" style="position:relative; bottom:8px;">
                                            <label for="quant_reviews" style="font-weight:bold; font-family: 'Arial Black', Gadget, sans-serif;">Reviews:</label>
                                            <p style="font-style:italic; font-size:17px;"><span style="text-decoration:underline; font-weight:bold;"><a href="reviews_usuario.php?id_usuario=<?php echo $id_usuario ?>"><?php echo $quant_reviews; ?></span></a> review(s) feitas no momento!</p>
                                            <hr style="position:relative; bottom:5px;" class="rounded">
                                            <div class="form-group" style="position:relative; bottom:8px;">
                                                <label for="quant_reviews" style="font-weight:bold; font-family: 'Arial Black', Gadget, sans-serif;">Criação da conta:</label>
                                                <p style="font-style:italic; font-size:17px;">Conta criada dia: <span style="text-decoration:underline; font-weight:bold;"><?php echo $data_formatada; ?></span>.<br> há <span style="text-decoration:underline; font-weight:bold;"><?php echo $mensagem ?></p></span>

                                                <hr style="position:relative; bottom:5px;" class="rounded">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif;?> 
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

                </body>
                </html>
