<html>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css2/estilos.css">
    <title></title>
    <head>
    </head>
    <body style="background-color:#242629">
        <script>
            function ConfirmDelete()
            {
                return confirm("Você tem certeza que quer excluir este comentário?");
            }
        </script> 
        <?php
        session_start();
        $comentariosPorPagina = 10;
        $nome_usuario1 = array();

        if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
            session_unset();
            echo "<script>
                alert('Esta página só pode ser acessada por usuário logado');
                window.location.href = 'login.php';
                </script>";
        }
        require('conecta.php');
        $logado = $_SESSION['login'];
        $adicionar = '';
        if ($_SESSION['login'] == 'gabridestiny@hotmail.com') {
            $adicionar = "<a class='dropdown-item' href='adicionar_jogos.php'>Adicionar Jogo</a>";
            $remover = "<small style='margin-right:5px; color:darkgrey;'>Remove</small>";
        } else {
            
        }

        if (isset($_GET['id_jogo1'])) {
            $id_jogo1 = $_GET['id_jogo1'];
            $sql = "SELECT * FROM games WHERE id_jogo = $id_jogo1";
            $resultado = $conecta->query($sql);

            if ($resultado->num_rows == 1) {
                $linha = $resultado->fetch_assoc();
            } else {
                echo "Game not found";
            }
        } else {
            echo "Missing game ID";
        }
        $sql2 = "SELECT * FROM reviews WHERE id_jogo= $id_jogo1 ORDER BY total_reviews DESC";
        $resultado = $conecta->query($sql2);
        $id_usuario = array();
        $txtreview = array();
        if ($resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                if ($linha != null) {
                    $id_usuario[] = $linha['id_usuario'];
                    $txtreview[] = $linha['texto_review'];
                    $id_review[] = $linha['id_review'];
                }
            }
        }

        for ($l = 0; $l < sizeOf($id_usuario); $l++) {
            $sql3 = "SELECT avaliacao_total FROM avaliacao WHERE id_usuario='$id_usuario[$l]' and id_jogo='$id_jogo1'";
            $resultado = $conecta->query($sql3);
            if ($resultado->num_rows > 0) {
                while ($linha = $resultado->fetch_assoc()) {
                    if ($linha != null) {
                        $nota_review[$l] = $linha['avaliacao_total'];
                    }
                }
            }
        }
        $comentario = array();
        for ($i = 0; $i < sizeOf($id_usuario); $i++) {
            $sql3 = "SELECT nome_usuario FROM usuario WHERE id_usuario='$id_usuario[$i]'";
            $resultado = $conecta->query($sql3);
            if ($resultado->num_rows > 0) {
                while ($linha = $resultado->fetch_assoc()) {
                    if ($linha != null) {
                        $nome_usuario1[$i] = $linha['nome_usuario'];
                    }
                }
            }
        }
        if (sizeOf($txtreview) > 0) {
            for ($h = 0; $h < sizeOf($txtreview); $h++) {
                $sql2 = "SELECT horario_review FROM reviews WHERE id_usuario = '$id_usuario[$h]' and id_jogo='$id_jogo1'";
                $resultado = $conecta->query($sql2);
                if ($resultado->num_rows > 0) {
                    date_default_timezone_set('America/Sao_Paulo');
                    $row = $resultado->fetch_assoc();
                    $postedTimeUnix[$h] = strtotime($row['horario_review']);
                } else {
                    
                }

                // 2. Get current timestamp

                $currentTimeUnix = time();

                // 3. Calculate time difference in seconds
                $timeDiffSeconds[$h] = $currentTimeUnix - $postedTimeUnix[$h];

                // 4. Convert time difference to appropriate units (seconds, minutes, hours, days)
                $minutos[$h] = floor($timeDiffSeconds[$h] / 60);
                $horas[$h] = floor($minutos[$h] / 60);
                $dias[$h] = floor($horas[$h] / 24);

                // 5. Construct the time-ago message
                if ($minutos[$h] < 1) {
                    $mensagem[$h] = "agora!";
                } else if ($minutos[$h] < 60) {
                    $mensagem[$h] = "$minutos[$h] minutos atrás!";
                } else if ($horas[$h] < 24) {
                    $mensagem[$h] = "$horas[$h] horas atrás!";
                } else {
                    $mensagem[$h] = "$dias[$h] dias atrás!";
                }
            }
        } else {
            $mensagem[] = '';
        }
        $sql = "SELECT MAX(total_reviews) as total_review FROM reviews";
$resultado = $conecta->query($sql);

if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $total_reviews = $linha['total_review'];
    }

    
    $cont = 1;

    $userIDs = array_keys($id_usuario); // Get an array of user IDs
    $commentIDs = array_keys($txtreview); // Get an array of comment IDs

    // Replace this with your actual code to get the current user ID securely
    $currentUserId = $_SESSION['id_usuario'];
    $currentUserEmail = $_SESSION['login']; // Assuming 'login' stores the user's email

    foreach ($userIDs as $index => $userID) {

        $comentario[$index] = "<div class='card p-3 mb-3 comentarioreview' style='background-color:darkgrey; background: #D8D8D8; border:5px solid;
                                border-image-slice: 1;
                                border-width:5px;
                                border-image-source: linear-gradient(to left, darkslategrey, black);'>
  <div class='d-flex justify-content-between align-items-center'>

    <div class='user d-flex flex-row align-items-center'>

      <img src='https://static.vecteezy.com/system/resources/previews/019/879/186/non_2x/user-icon-on-transparent-background-free-png.png' width='40' height='40' class='user-img rounded-circle' style='margin-right:7px; object-fit:cover; position:static; margin-bottom:auto; bottom:5px;'>
      <span><small class='font-weight-bold' style='font-weight:bold; font-size:17px; '>" . "$nome_usuario1[$index]" . "</small>: <small class='font-weight-bold' style='font-family:Georgia, serif; text-overflow: ellipsis; word-break: break-all;
word-break: break-word; padding-right:15px;'>" . "$txtreview[$index]" . "</small></span>

    </div>






    <p class='' style='text-align:right; position:relative; border:2px solid white; top:10px; font-weight:bold; text-decoration:italic; color:white; display:flex; justify-content:center; font-size:20px; background-color:#1B1212; padding-left:3px; padding-right:3px; border-radius:20%;'>" . "$nota_review[$index]" . "</p>
  </div>


  <div class='action d-flex justify-content-between mt-2 align-items-center'>";

        // Check if the current user ID or email matches the comment's user ID
        if (($currentUserId === $id_usuario[$commentIDs[$index]]) || ($currentUserEmail === "gabridestiny@hotmail.com")) {
            $comentario[$index] .= "<form action='deletar_comentario.php' onsubmit='return confirm('Are you sure?');' method='post' style='display: inline-block;'>
              <input type='hidden' name='id_review' value='" . $id_review[$commentIDs[$index]] . "'>
              <button Onclick='return ConfirmDelete();' type='submit' name='delete' class='btn btn-danger btn-sm' style='font-size: 12px; '>Excluir</button>
            </form>
            <div class='icons align-items-center' style='position:relative; bottom:4px;'>
        <p style='text-align:left'><span style='font-weight:bold;'> Postado há</span>: $mensagem[$index]</p> 
        </div>
            </div>
                </div>";
                    
        }else {
            $comentario[$index] .= " 

    <div class='icons align-items-center' style='text-align:right; margin-left:auto; position:relative; bottom:4px;'>
      <p style='text-align:left'><span style='font-weight:bold; '> Postado há</span>: $mensagem[$index]</p> 
    </div>
  </div>
</div>";
            
        }

        

        // Remove the debugging echo statement (it was just for troubleshooting)
        // echo "Current User ID: $currentUserId, User ID: $userID, Comment ID: " . $id_comentario[$commentIDs[$index]] . "<br>";
    }
}



        $sql = "SELECT * FROM games WHERE id_jogo= $id_jogo1";
        $resultado = $conecta->query($sql);
        if ($resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                if ($linha != null) {
                    $link = $linha["img_jogo"];
                    $generos = $linha["generos"];
                    $desc = $linha["desc_jogo"];
                    $nomejogo = $linha["nome_jogo"];
                    $id_jogo = $linha["id_jogo"];
                    $data = $linha["data_lancamento"];
                    $data = date("d/m/Y", strtotime($data));
                    $dev = $linha["desenvolvedor"];
                    $publisher = $linha["publisher"];
                    $_SESSION['id_jogo'] = $linha["id_jogo"];
                } else {
                    
                }
            }
        }

        $sql = "SELECT  AVG(avaliacao_total) FROM avaliacao WHERE id_jogo = '$id_jogo'";
        $result = $conecta->query($sql);
        while ($row = mysqli_fetch_array($result)) {
            $media = (int) $row['AVG(avaliacao_total)'];
        }
        if ($media == NULL) {
            $media = 0;
        }
        for ($h = 0; $h < 1; $h++) {
            // 1. Fetch and convert posted time to timestamp
            $sql2 = "SELECT horario_postado FROM games WHERE id_jogo = '$id_jogo'";
            $result = $conecta->query($sql2);
            if ($result->num_rows > 0) {
                date_default_timezone_set('America/Sao_Paulo');
                $row = $result->fetch_assoc();
                $postedTimeUnix[$h] = strtotime($row['horario_postado']);
            } else {
                
            }

            // 2. Get current timestamp
            $currentTimeUnix = time();

            // 3. Calculate time difference in seconds
            $timeDiffSeconds[$h] = $currentTimeUnix - $postedTimeUnix[$h];

            // 4. Convert time difference to appropriate units (seconds, minutes, hours, days)
            $minutos[$h] = floor($timeDiffSeconds[$h] / 60);
            $horas[$h] = floor($minutos[$h] / 60);
            $dias[$h] = floor($horas[$h] / 24);

            // 5. Construct the time-ago message
            if ($minutos[$h] < 1) {
                $mensagem[$h] = "Agora!";
            } else if ($minutos[$h] < 60) {
                $mensagem[$h] = "$minutos[$h] minutos atrás!";
            } else if ($horas[$h] < 24) {
                $mensagem[$h] = "$horas[$h] horas atrás!";
            } else {
                $mensagem[$h] = "$dias[$h] dias atrás!";
            }
        }
        $totalComentarios = sizeof($id_usuario);
$totalPaginas = ceil($totalComentarios / $comentariosPorPagina);
$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$indiceInicial = ($paginaAtual - 1) * $comentariosPorPagina;
$indiceFinal = min($indiceInicial + $comentariosPorPagina - 1, $totalComentarios - 1);

// Loop para exibir apenas os comentários da página atual

    

// Links de navegação entre páginas

        ?>
        <div style="overflow-x: hidden;">

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
                                <img src="https://static.vecteezy.com/system/resources/previews/019/879/186/non_2x/user-icon-on-transparent-background-free-png.png" style="width:80px; height:50px; text-align:right">
                            </a>

                            <div class="dropdown-menu dropdown-menu-end position-absolute" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item dropdown" href="#"> Ver perfil</a>
                                <a class="dropdown-item" href="#"> Editar perfil</a>
                                <?php echo $adicionar ?>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <br>
            <div class="row" style="margin-top:30px;">
                <div class="col-md-6 col-lg-4 ; card bordas float-start centralizar" style="max-width: 80%; width: 500px; padding: 0; overflow: auto;">
                    <img src="<?php echo $link ?>" class="img-fluid imagem-f" alt="..." >

                    <div class="card-body" style="background: #E8E8E8; border:5px solid; border-image-slice: 1; border-width:5px; border-left:0px; border-right:0px; border-bottom:0px; border-image-source: linear-gradient(to right, darkslategrey, black);">
                        <h5 class="card-title d-flex justify-content-center" style="font-weight: bold"><?php echo $nomejogo . ' | ' . $data ?></h5>
                        <p class="card-text" style="text-align:justify;"><?php echo $desc ?><br><p><span style="font-weight:bold">Generos: </span><?php echo $generos . "." ?><br><span style="font-weight:bold">Desenvolvedora e Publisher: </span><?php echo $dev . ", " . $publisher . "."; ?><br><span style="font-weight:bold">Postado há: </span><?php echo $mensagem[0]; ?><p class="rating-box mx-auto" style='display:flex; justify-content:center; font-size:20px;'>Nota média: <?php echo"<span class=' mx-auto' style='text-decoration: underline; color:white; display:flex; justify-content:center; font-size:20px; background-color:#1B1212; padding-left:3px; padding-right:3px; border-radius:20%;'>$media" ?></p>
                    </div>
                </div>
                <div class="col-lg-7 col-md-8 col-sm-12 col-12 col review123" style="max-width: 75%; margin-top:35px;">
                    <form action="recebe_review.php" method='POST' class="textoreview">
                        <div>
                            <label for="vaso" class="form-label" style='color:white; '>Review do jogo: </label>
                            <textarea maxlength="2000" rows="5" class="form-control mb-2" required id="vaso" name="review" placeholder="Escreva sua review:" style="background: #D8D8D8; border:5px solid;
                                      border-image-slice: 1;
                                      border-width:5px;
                                      border-image-source: linear-gradient(to left, darkslategrey, black);display:flex; margin-right:100%;"></textarea>
                        </div>
                        <div class="mb-3 col-sm">
                            <label for="reviewScore" class="form-label" style='color:white;'>Nota do jogo: </label>
                            <input type="number" class="form-control" id="reviewScore" name="nota_review" min="0" max="100" placeholder="Digite sua nota para o jogo" required>
                        </div>
                        <input type="hidden" name="id_jogo1" value="<?php echo $id_jogo; ?>" required>
                        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>" required>
                        <button type="submit" class="btn btn-primary botaosub" >Enviar</button>
                    </form>

                    </form>
                    <hr class="rounded2">
                    <div class="container" style="max-width:100%; margin-left:auto; margin-right:5%;">

                        <div class="row d-flex justify-content-end">

                            <div class="col-md-4">

                            </div>
                           
                                <?php
                               for ($i = $indiceInicial; $i <= $indiceFinal; $i++) {
    // Se o índice estiver dentro do intervalo válido
    if ($i < $totalComentarios) {
        // Exibir o comentário correspondente
        echo $comentario[$i];
    }
}
if($totalPaginas == 1) {
                
            }else {
echo "<ul class='pagination justify-content-center'>";
for ($pagina = 1; $pagina <= $totalPaginas; $pagina++) {
    echo "<li class='page-item'><a style='font-size:30px; background-color:white; border: 2px solid black; color:black; border-radius:20px; margin-left:5px; padding-right:3px; padding-left:3px;' class='page-link'href='jogo_mostrar.php?id_jogo1=$id_jogo1&pagina=$pagina'>$pagina</a></li>";
}
echo "</ul>";
            }
                                ?>
                               
                                ?>


                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div><br>







        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script src="javascriptsite.js"></script> 
    </body>
</html>
<?php $conecta->close(); ?>