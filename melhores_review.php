<html>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css2/estilos.css">
    <title>Melhores Avaliados</title>
    <link rel="icon" href="https://static.thenounproject.com/png/122214-200.png">
    <head>
    </head>
    <body style="background-color:#242629">
        <?php
        session_start();

        if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
            session_unset();
            echo "<script>
        alert('Esta página só pode ser acessada por usuário logado');
        window.location.href = 'login.php';
        </script>";
        }

        $adicionar = '';
        if ($_SESSION['login'] == 'gabridestiny@hotmail.com') {
            $adicionar = "<a class='dropdown-item' href='adicionar_jogos.php'>Adicionar Jogo</a>";
        }

        $logado = $_SESSION['login'];
        require('conecta.php');

        $limit = 10  ; 

        $sql = "SELECT * FROM games ORDER By avaliacao_media DESC";
        $resultado = $conecta->query($sql);


        $total_jogos = $resultado->num_rows;


        $total_paginas = ceil($total_jogos / $limit);

        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1; 
        $inicio = ($pagina - 1) * $limit;

         

        $resultado = $conecta->query($sql);


        $link = array();
        $generos = array();
        $desc = array();
        $nomejogo = array();
        $id_jogo = array();
        $media = array();
        $mensagem = array();


        if ($resultado->num_rows > 0) {
            $cont = 0;
            while ($linha = $resultado->fetch_assoc()) {
                $link[$cont] = $linha["img_jogo"];
                $generos[$cont] = $linha["generos"];
                $desc[$cont] = $linha["desc_jogo"];
                $nomejogo[$cont] = $linha["nome_jogo"];
                $id_jogo[$cont] = $linha["id_jogo"];
                $horario_jogo = $linha["horario_postado"];
                $media[$cont] = $linha['avaliacao_media'];
                $cont++;
            }
        }


       for ($h = 0; $h < sizeOf($id_jogo); $h++) {
            
            $sql2 = "SELECT horario_postado FROM games WHERE id_jogo = '$id_jogo[$h]'";
            $result = $conecta->query($sql2);
            if ($result->num_rows > 0) {
                date_default_timezone_set('America/Sao_Paulo');
                $row = $result->fetch_assoc();
                $postedTimeUnix[$h] = strtotime($row['horario_postado']);
            } else {
                
            }

            
            $currentTimeUnix = time();

            
            $timeDiffSeconds[$h] = $currentTimeUnix - $postedTimeUnix[$h];

            
            $minutos[$h] = floor($timeDiffSeconds[$h] / 60);
            $horas[$h] = floor($minutos[$h] / 60);
            $dias[$h] = floor($horas[$h] / 24);

            
            if ($minutos[$h] < 1) {
                $mensagem[$h] = "agora!";
            } else if ($minutos[$h] < 60) {
                $mensagem[$h] = "$minutos[$h] minuto(s) atrás!";
            } else if ($horas[$h] < 24) {
                $mensagem[$h] = "$horas[$h] hora(s) atrás!";
            } else {
                $mensagem[$h] = "$dias[$h] dia(s) atrás!";
            }
        }


        for ($i = 0; $i < $cont; $i++) {
            ($i % 2 == 0) ? ($fade = "fadeInFromLeft") : ($fade = "fadeInFromRight");
            if ($i == 0) {
                $melhor1[$i] = "<div class='card mb-3 mx-auto responsivo $fade' style='margin-top:50px;'>
            <div class='row g-0'>
                <div class='col-md-4'>
                    <a href='jogo_mostrar.php?id_jogo1=$id_jogo[$i]'>
                        <img src='$link[$i]' class='img-fluid imagem1' style='width:100%; height:100%; max-height:220px; object-fit: fill;'alt='...'>
                    </a>
                </div>
                <div class='col-md-8 d-flex'>
                    <div class='card-body' style='background-color:#9B9CA6; max-height:220px; overflow:auto;'>
                        <h5 class='card-title container-fluid' style='font-weight:bold; font-size:23px; text-align:center; text-decoration:underline;'>$nomejogo[$i]</h5>
                        <p class='card-text container-fluid' style='flex-grow: 1;object-fit:fill; text-align:justify;'>$desc[$i]<br><span style='font-weight:bold'>Gêneros</span>: $generos[$i].<br> <span style='font-weight:bold'>Postado há</span>: $mensagem[$i]</p>
                        <p class='rating-box mx-auto' style='display:flex; justify-content:center; font-size:20px;'>Nota média: <span class='mx-auto' style=' text-decoration: underline; color:white; display:flex; justify-content:center; font-size:20px; background-color:#1B1212; padding-left:3px; padding-right:1px; border-radius:20%; text-decoration:underline; '>$media[$i]</span></p>
                        <p style='display:flex; justify-content:right; overflow:auto;' class='card-text container-fluid'><small class='text-body-secondary' style='display:flex; justify-content:flex-end'>
                            <form action='jogo_mostrar.php' method='get'>
                                <input type='hidden' name='id_jogo1' value='$id_jogo[$i]'>
                                <button type='submit' class='btn btn-primary mx-auto vermais' style='font-weight:bold; font-style:italic;  text-shadow: 2px 2px #000; font-size:18px; margin:-13px;'>Ver detalhes</button>
                            </form>
                        </small></p>
                    </div>
                </div>
            </div>
        </div>";
            } else {
                $melhor1[$i] = "<div class='card mb-3 mx-auto responsivo $fade' style='margin-top:50px;'>
            <div class='row g-0'>
                <div class='col-md-4'>
                    <a href='jogo_mostrar.php?id_jogo1=$id_jogo[$i]'>
                        <img src='$link[$i]' class='img-fluid imagem1' style='width:100%; height:100%; max-height:220px; object-fit: fill;'alt='...'>
                    </a>
                </div>
                <div class='col-md-8 d-flex'>
                    <div class='card-body' style='background-color:#9B9CA6; max-height:220px; overflow:auto;'>
                        <h5 class='card-title container-fluid' style='font-weight:bold; font-size:23px; text-align:center; text-decoration:underline;'>$nomejogo[$i]</h5>
                        <p class='card-text container-fluid' style='flex-grow: 1;object-fit:fill; text-align:justify;'>$desc[$i]<br><span style='font-weight:bold'>Gêneros</span>: $generos[$i].<br> <span style='font-weight:bold'>Postado há</span>: $mensagem[$i]</p>
                        <p class='rating-box mx-auto' style='display:flex; justify-content:center; font-size:20px;'>Nota média: <span class='mx-auto' style='text-decoration: underline; color:white; display:flex; justify-content:center; font-size:20px; background-color:#1B1212; padding-left:3px; padding-right:1px; border-radius:20%;'>" . $media[$i] . "<span></p></h5>
                        <p style='display:flex; justify-content:right; overflow:auto;' class='card-text container-fluid'><small class='text-body-secondary' style='display:flex; justify-content:flex-end'>
                            <form action='jogo_mostrar.php' method='get'>
                                <input type='hidden' name='id_jogo1' value='$id_jogo[$i]'>
                                <button type='submit' class='btn btn-primary mx-auto vermais' style='font-weight:bold; font-style:italic;  text-shadow: 2px 2px #000; font-size:18px; margin:-13px;'>Ver detalhes</button>
                            </form>
                        </small></p>
                    </div>
                </div>
            </div>
        </div>";
            }
        }

       
 require ('conecta.php');
        if (isset($_SESSION['id_usuario'])) {
            $id_usuario = $_SESSION['id_usuario'];
        }

        $sql = "SELECT img_perfil from usuario where id_usuario = $id_usuario";
        $resultado = $conecta->query($sql);
        if ($resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                $img_perfil = $linha['img_perfil'];
            }
        }
        ?>
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
                            <a class="nav-link active" style="color:white; font-size:26px; font-weight:bold;" href="lista_jogos.php">Lista</a>
                        </li>
                    </ul>
                </div>

                <ul class="navbar-nav ms-auto">  <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="thumbnail" src="<?php echo $img_perfil; ?>" style="width:50px; height:50px; text-align:right; border-radius:50%; margin-right:7px; border: 2px solid black;">
                        </a>

                        <div class="dropdown-menu dropdown-menu-end position-absolute" aria-labelledby="navbarDropdown">
                           <a class="dropdown-item" href="pagina_usuario.php?id_usuario=<?php echo $id_usuario; ?>">Ver perfil</a>
                            <a class="dropdown-item" href="editar_usuario.php"> Editar perfil</a>
                            <?php echo $adicionar ?>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <h1 class="mx-auto letra" style="color:white; margin-top:100px; text-align:center; "><span style="background-color:#343434; padding-left:30px; padding-right:30px; border-radius:10px;  ">⧙ Melhores avaliados ⧘</span></h1> 
        <?php
         for ($i = ($pagina - 1) * $limit; $i < min($pagina * $limit, $total_jogos); $i++) {
    echo $melhor1[$i];
}
        
if (isset($total_paginas) && $total_paginas > 1) {
    echo "<ul class='pagination justify-content-center'>";
    $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

    
    $inicio = max(1, $pagina_atual - 8);

    
    $fim = min($total_paginas, $inicio + 9);

    
    if ($fim == $total_paginas && $total_paginas > 9) {
        $inicio = max(1, $fim - 9);
    }

    
    if ($inicio > 1) {
        echo "<li class='page-item style='height:40px;'><a class='custom-page-link1' style='color: white; bottom:1%;' href='melhores_review.php?pagina=" . ($inicio - 1) . "'>&laquo;</a></li>";
    }

    
    for ($i = $inicio; $i <= $fim; $i++) {
        $class = ($i == $pagina_atual) ? 'page-item active' : 'page-item';
        $style = ($i == $pagina_atual) ? 'background-color: grey;' : '';
        echo "<li class='$class'><a style='font-size:30px; border: 2px solid black; color:black; border-radius:20px; margin-left:5px; padding-right:3px; padding-left:3px; $style' class='page-link' href='melhores_review.php?pagina=$i'>$i</a></li>";
    }

    
    if ($fim < $total_paginas) {
        echo "<li class='page-item style='height:40px;'><a class='custom-page-link2' style='color: white; bottom:1%;' href='melhores_review.php?pagina=" . ($fim + 1) . "'>&raquo;</a></li>";
    }

    echo "</ul>";
} 
        ?>


        <br>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script src="javascriptsite.js"></script> 
    </body>
</html>
<?php
$conecta->close();
?>