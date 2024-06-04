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
                    allowOutsideClick: false // Evita fechar ao clicar fora do alerta
                }).then((result) => {
                    window.location.href = 'login.php';
                });
            });
          </script>";
    exit; // Certifique-se de parar a execução do script após redirecionar
}
$logado = $_SESSION['login'];
$adicionar = '';
if ($_SESSION['login'] == 'gabridestiny@hotmail.com') {
    $adicionar = "<a class='dropdown-item' href='adicionar_jogos.php'>Adicionar Jogo</a>";
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
<html>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css2/estilos.css">
    <title>Lista de Jogos</title>
    <link rel="icon" href="https://static.thenounproject.com/png/122214-200.png">
    <head>
        <style>
            .card:hover {
                box-shadow: 0 0px 12px 0 black;
            }
            button:hover {
                box-shadow: 0 0px 16px 0 grey;
                font-size: 16.5px;
                border-color: grey; /* Cor inicial da borda */
                transition: border-color 0.3s ease;
            }

            button:hover {
                border-color: white; /* Cor da borda ao passar o mouse */
            }
            button:link {
                color:white;
            }
        </style>
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

        
            <h1 class="mx-auto letra2" style="color:white; margin-top:100px; text-align:center; "><span style="background-color:#343434; padding-left:30px; padding-right:30px; border-radius:10px; text-shadow: 3px 3px black; font-family:monospace;"> ⚡︎ Gêneros ⚡︎ </span></h1>
            <div class="fadeInFromBottom">
            <div class="generos">
               <?php
$genres = array(
    "MOBA",
    "Point-and-click",
    "Luta",
    "Shooter",
    "Música",
    "Plataforma",
    "Quebra-Cabeça",
    "Corrida",
    "RPG",
    "Simulação",
    "Esporte",
    "Estratégia",
    "Tático",
    "Hack and slash",
    "Quiz/Trivia",
    "Pinball",
    "Aventura",
    "Indie",
    "Arcade",
    "Visual Novel",
    "Estratégia em turnos",
    "Estratégia em tempo real",
    "Jogo de carta/tabuleiro",
    "Negócios",
    "Drama",
    "Não-Ficção",
    "Sandbox",
    "Educacional",
    "Crianças",
    "Mundo Aberto",
    "Guerra",
    "Jogar com amigos",
    "Exploração",
    "Erótico",
    "Mistério",
    "Romance",
    "Ação",
    "Fantasia",
    "Ficção Científica",
    "Terror",
    "Suspense",
    "Sobrevivência",
    "Histórico",
    "Furtividade",
    "Comédia"
);


                foreach ($genres as $genre) {
                    echo '
            <div class="col-lg-8 col-md-9">
            <a href="lista_jogos.php?genero=' . urlencode($genre) . '">
								<button id="button" type="button" style="color:white; white-space: nowrap; background-color:black; font-family:fantasy; font-weight:bold; border:2px solid #707070; width:225px; height:60px; margin-right:9px; padding-right:11px; " class="btn mb-2 mb-m-0 btn-primary btn-block genre-button" onclick="window.location.href=\'lista_jogos.php?genero=' . urlencode($genre) . '\'"><span style=color:white;>' . $genre . '</span>
									<div class="icon d-flex align-items-center justify-content-center">
										<i class="ion-ios-heart"></i>
                                                                            </a>    
								
								
           
							
							</div>'
                    ;
                }
                ?>
                <script>
                    var buttons = document.querySelectorAll(".genre-button");

                    buttons.forEach(function (button) {
                        button.addEventListener("mouseover", function () {
                            this.style.borderColor = "white";
                        });

                        button.addEventListener("mouseout", function () {
                            this.style.borderColor = "#707070";
                        });
                    });
                </script>
            </div>
        </div>
    </div>

    <style>
        .lista-reviews {
            margin-top: 20px; /* Ajuste a margem superior conforme necessário */
        }


        .btn-dark:hover {
            background-color: #ffffff;
            color: #000000;
            border-color: #000000;
        }
    </style>


























    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>