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
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
}
?>
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
        <h1 class="mx-auto letra2" style="color:white; margin-top:100px; text-align:center; "><span style="background-color:#343434; padding-left:30px; padding-right:30px; border-radius:10px;  ">⧙ Minhas Reviews ⧘</span></h1>
        <?php
        if (!isset($_GET['page'])) {

            $page_number = 1;
        } else {

            $page_number = $_GET['page'];
        }
        $limit = 3;
        $initial_page = ($page_number - 1) * $limit;
        $getQuery = "SELECT * from reviews where id_usuario=$id_usuario";

        $result = mysqli_query($conecta, $getQuery);

        $total_rows = mysqli_num_rows($result);

// get the required number of pages

        $total_pages = ceil($total_rows / $limit);
        $getQuery = "SELECT * FROM reviews where id_usuario=$id_usuario ORDER BY total_reviews DESC LIMIT " . $initial_page . ',' . $limit;

        $result = mysqli_query($conecta, $getQuery);

//display the retrieved result on the webpage  
        echo "<p style='margin-top:50px;'></p>";
        $cont = 0;
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $id_jogo[] = $row['id_jogo'];
                $texto_review[] = $row['texto_review'];
                $id_review[] = $row['id_review'];
                $review = "positiva";
                $sql2 = "SELECT * FROM games WHERE id_jogo='$id_jogo[$cont]'";
                $resultado = $conecta->query($sql2);
                if ($resultado->num_rows > 0) {
                    while ($linha = $resultado->fetch_assoc()) {
                        $nome_jogo[] = $linha['nome_jogo'];
                        $link[] = $linha['img_jogo'];
                    }
                }
                $sql3 = "SELECT avaliacao_total FROM avaliacao WHERE id_jogo='$id_jogo[$cont]' and id_usuario=$id_usuario";
                $resultado = $conecta->query($sql3);
                if ($resultado->num_rows > 0) {
                    while ($linha = $resultado->fetch_assoc()) {
                        $avaliacao_usuario[] = $linha['avaliacao_total'];
                        if ($avaliacao_usuario[$cont] < 50) {
                    $review = "negativa";
                } else {
                    $review = "positiva";
                }
                    }
                }
                

                
               
                for ($h = 0; $h < sizeOf($id_jogo); $h++) {
                    // 1. Fetch and convert posted time to timestamp
                    $sql2 = "SELECT horario_review FROM reviews WHERE id_usuario = '$id_usuario' and id_jogo='$id_jogo[$h]'";
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
                


echo "<div class='card mx-auto text-center balala2' style='margin-bottom:30px; background-color:#151922; '>
  <div class='card-header' style='font-size:26px; color:white; font-weight:bold; text-decoration:underline; text-shadow: 0px 0px 5px black, 0px 0px 8px black;  '>
    " . $nome_jogo[$cont] . "
  </div>
  
  <div class='card-body cardBackground2' style='text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white; background-image: url(".$link[$cont]."'>
    <h5 class='card-title' style='font-weight:bold;'>Review " . "$review" . " | Nota: <span style='border:1px solid black; padding-left:3px; color:white; background-color:#1B1212; padding-right:3px; text-decoration:underline; border-radius:4px; text-shadow: none; '>" . $avaliacao_usuario[$cont] . "</span> </h5><p></p>
    <p class='card-text text-center' style='text-align:justify; margin-bottom:18px; font-size:18px; opacity:1; '>''" . '' . $texto_review[$cont] . "''</p>
      <p class='card-text text-center' style='text-align:justify; margin-bottom:10px;'><span style='font-weight:bold;  font-size:17px;' </span>Postado há: " . '' . $mensagem[$cont] . "</p>
    <div style='display:flex; justify-content: center; position:relative; top:5px; '>  <a href=jogo_mostrar.php?id_jogo1=" . $id_jogo[$cont] . " class='btn btn-primary' style='margin-right:10px; margin-bottom:16px;'><span style='text-shadow: 2px 2px black; font-weight:bold; font-size:17px;'>Ir para o jogo</a></span>
      <form action='deletar_comentario.php' onsubmit='return confirm('Are you sure?');' method='post' style=''>
              <input type='hidden' name='id_review' value='$id_review[$cont]'>
              <input type='hidden' name='jogo_excluir' value='$id_jogo[$cont]'>
              <button style='margin-top:3px;'Onclick='return ConfirmDelete();' type='submit' name='delete' class='btn btn-danger btn-sm' style='font-size:10px; max-height:90px;'  '><span style='text-shadow: 2px 2px black; font-weight:bold; font-size:17px;'>Excluir</a></span>
              
            </form>
  </div>
</div> </div>";
                $cont += 1;
                
            }

            $cont2 = 0;
            if ($total_pages == 1) {
                
            } else {
                $link_style = 'style="font-size:30px; background-color:white; border: 2px solid black; color:black; border-radius:20px; margin-left:5px; padding-right:3px; padding-left:3px;"';

                echo '<div style="text-align: center; margin-bottom:10px;">';
                for ($page_number = 1; $page_number <= $total_pages; $page_number++) {
                    $url = "reviews_usuario.php?page=" . $page_number;
                    echo "<a $link_style href='$url'>$page_number</a>";
                    if ($page_number < $total_pages) {
                        echo " "; // Add a space between page numbers
                    }
                }
                echo '</div>';
            }
        }
        $sql3 = "SELECT avaliacao_total FROM avaliacao WHERE id_usuario=$id_usuario";
                $resultado = $conecta->query($sql3);
                if ($resultado->num_rows > 0) {
                    while ($linha = $resultado->fetch_assoc()) {
                        
                        
                    }
                } else {
                    echo "<div class='card mx-auto balala' style='height:30%; margin-top:60px; border:4px solid black; border-radius:10'>
      <div class='card-body cardBackground d-flex flex-column justify-content-center align-items-center'>
        <h2 class='card-title text-center' style='color:white; text-shadow: 1px 1px black; font-weight:bold;'>Você não possui reviews!</h2>
        <p class='copy card-text text-center' style='color:white; text-shadow: 1px 1px black; font-size:18px; text-decoration:underline;'>Clique abaixo para ver os jogos cadastrados!</p>
        <div class='d-flex justify-content-between'>
        
          <a href='jogos_recentes.php' class='btn btn-dark' style='margin: 0 auto; color:white; margin-right:15px; text-shadow: 1px 1px black; max-width:60%; max-height:100%;'>Jogos Recentes</a>
            <a href='melhores_review.php' class='btn btn-dark' style='margin: 0 auto; color:white; margin-left:15px; text-shadow: 1px 1px black; max-width:60%; max-height:100%;'>Melhores Avaliados</a>

</div>
      </div>
    </div>";
            
                    
                
                 
                }
        ?>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </body>
</html>
