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
?>
<script>
                    function updateImageSources() {
                        var width = window.innerWidth;
                        var images = document.querySelectorAll('.carousel-image');

                        images.forEach(function (img) {
                            var artworkSrc = img.getAttribute('data-artwork');
                            var jogoSrc = img.getAttribute('data-jogo');
                            if (width < 650) {
                                img.setAttribute('src', jogoSrc);
                            } else {
                                img.setAttribute('src', artworkSrc);
                            }
                        });
                    }

                    window.addEventListener('resize', updateImageSources);
                    window.addEventListener('load', updateImageSources);
                </script>
<?php
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
$sql = "SELECT * FROM games ORDER BY avaliacao_media DESC LIMIT 3";
$resultado = $conecta->query($sql); // Ajuste a variável $conn de acordo com sua conexão

if ($resultado->num_rows > 0) {
    $cont = 0; // Inicializar contador fora do loop
    while ($linha = $resultado->fetch_assoc()) {
        $link[$cont] = $linha["imagem_artwork"];
        $nomejogo[$cont] = $linha["nome_jogo"];
        $id_jogo[$cont] = $linha["id_jogo"];
        $link_pequeno[$cont] = $linha["img_jogo"];
        $media[$cont] = $linha["avaliacao_media"];
        $cont++; // Incrementar o contador a cada iteração
    }
} else {
    echo "Nenhum jogo encontrado.";
}

$sql = "SELECT * FROM games ORDER By id_jogo ASC LIMIT 8";
$resultado = $conecta->query($sql); // Ajuste a variável $conn de acordo com sua conexão

if ($resultado->num_rows > 0) {
    $cont = 0; // Inicializar contador fora do loop
    while ($linha = $resultado->fetch_assoc()) {
        $link1[$cont] = $linha["img_jogo"];
        $id_jogo1[$cont] = $linha["id_jogo"];
        $descjogo[$cont] = $linha["desc_jogo"];

        $cont++; // Incrementar o contador a cada iteração
    }
} else {
    echo "Nenhum jogo encontrado.";
}
?>
<html>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css2/estilos.css">
    <title>Inicio</title>
    <link rel="icon" href="https://static.thenounproject.com/png/122214-200.png">
    <head>
        <style>

            carousel:hover {
                box-shadow: 0 0px 16px 0 grey;
                font-size: 17px;
                border-color: grey; /* Cor inicial da borda */
                transition: border-color 0.3s ease;
            }

            button:hover {
                border-color: white; /* Cor da borda ao passar o mouse */
            }

            .card:hover {
                box-shadow: 0 0px 12px 0 white;
            }
            button:hover {

            }

            button:hover {
                border-color: white; /* Cor da borda ao passar o mouse */
            }
            button:link {
                color:white;
            }

            .carousel-titulo {
                top: 13px;
                position: relative;
                font-size: 50px;
            }


            @media (max-width: 1900px) {
                .carousel-titulo {
                    font-size: 55px;
                }
            }
            @media (max-width: 1200px) {
                .carousel-titulo {
                    font-size: 50px;
                }
            }

            @media (max-width: 992px) {
                .carousel-titulo {
                    font-size: 48px;
                }
            }

            @media (max-width: 768px) {
                .carousel-titulo {
                    font-size: 44px;
                }
            }

            @media (max-width: 576px) {
                .carousel-titulo {
                    font-size: 40px;
                }
            }
            @media (max-width: 390px) {
                .carousel-titulo {
                    font-size: 35px;
                }
            }
        </style>
    </style>

</head>
<body style="background-color:#242629">
<?php
if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    session_unset();
    echo "<script>
            $(document).ready(function(){
                $('#loginModal').modal('show');
            });
            </script>";
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

    <br>
    <div class="fadeInFromBottom">
        <div class="main" style="z-index:-1; margin-top:60px;">
            <div id="meu-carrossel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?php echo $link[0]; ?>" data-artwork="<?php echo $link[0]; ?>" data-jogo="<?php echo $link_pequeno[0]; ?>" height="60%" width="90%" class="d-block mx-auto carousel-image" style="border-style: solid; object-fit:fill; border-width: 4px; border-image:linear-gradient(grey, black) 50;" loading='lazy' alt="Imagem <?php echo 0 + 1; ?>">
                        <div class="carousel-caption">
                            <h5 class="carousel-titulo destaque" style="top:13px; position:relative; "><?php echo $nomejogo[0] ?>™</h5><br>

                            <p class="rating-box gold mx-auto" style="display:flex; justify-content:center; font-size:30px; max-width:90px;">
                                <span class="mx-auto" style="padding-left:3px; border-radius:20%; white-space: nowrap;">
<?php echo $media[0]; ?>
                                </span><a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo[0] ?>"></p>
                            <button class="btn btn-primary saiba-mais-btn" style="position:relative; background-color:black; border-color:white; color:white; font-weight:bold; margin-top:5px;">Saiba mais</button>
                            </a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="<?php echo $link[1]; ?>" data-artwork="<?php echo $link[1]; ?>" data-jogo="<?php echo $link_pequeno[1]; ?>" height="60%" width="90%" class="d-block mx-auto carousel-image" style="border-style: solid; object-fit:fill; border-width: 4px; border-image:linear-gradient(grey, black) 50;" loading='lazy' alt="Imagem <?php echo 1 + 1; ?>">
                        <div class="carousel-caption">
                            <h5 class="carousel-titulo destaque" style="top:13px; position:relative; "><?php echo $nomejogo[1] ?>™</h5><br>

                            <p class="rating-box silver mx-auto" style="display:flex; justify-content:center; font-size:30px; max-width:90px;">
                                <span class="mx-auto" style="padding-left:3px; border-radius:20%; white-space: nowrap;">
<?php echo $media[1]; ?>
                                </span><a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo[1] ?>"></p>
                            <button class="btn btn-primary saiba-mais-btn" style="position:relative; background-color:black; border-color:white; color:white; font-weight:bold; margin-top:5px;">Saiba mais</button>
                            </a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="<?php echo $link[2]; ?>" data-artwork="<?php echo $link[2]; ?>" data-jogo="<?php echo $link_pequeno[2]; ?>" height="60%" width="90%" class="d-block mx-auto carousel-image" style="border-style: solid; object-fit:fill; border-width: 4px; border-image:linear-gradient(grey, black) 50;" loading='lazy' alt="Imagem <?php echo 2 + 1; ?>">
                        <div class="carousel-caption">
                            <h5 class="carousel-titulo destaque" style="top:13px; position:relative;"><?php echo $nomejogo[2] ?>™</h5><br>

                            <p class="rating-box bronze mx-auto" style="display:flex; justify-content:center; font-size:30px; max-width:90px;">
                                <span class="mx-auto" style="padding-left:3px; border-radius:20%; white-space: nowrap;">
<?php echo $media[2]; ?>
                                </span><a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo[2] ?>"></p>
                            <button class="btn btn-primary saiba-mais-btn" style="position:relative; background-color:black; border-color:white; color:white; font-weight:bold; margin-top:5px;">Saiba mais</button>
                            </a>
                        </div>
                    </div>
                </div>
                



                <button class="carousel-control-prev" type="button" data-bs-target="#meu-carrossel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#meu-carrossel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div><br><br>
            <div class="justify-content-center" style="text-align:center;">
                <a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo1[0] ?>">
                    <div class="card text-white bg-dark" id="card-foda" style="width:290px;">
                        <img src="<?php echo $link1[0] ?>" class="card-img-top" height="210px" loading='lazy' alt="...">
                        </a>
                        <div class="card-body cartao bg-dark" style="padding:10px;">
                            <p style="text-align:justify; max-height:140px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white"><?php echo $descjogo[0] ?></p>
                        </div>
                    </div>

                    <a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo1[1] ?>">
                        <div class="card text-white bg-dark" id="card-foda" style="width:290px;">
                            <img src="<?php echo $link1[1] ?>"  class="card-img-top" height="210px" loading='lazy' alt="...">
                            </a>
                            <div class="card-body cartao bg-dark" style="padding:10px;">
                                <p style="text-align:justify; max-height:140px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white"><?php echo $descjogo[1] ?></p>
                            </div>
                        </div>

                        <a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo1[2] ?>">
                            <div class="card text-white bg-dark" id="card-foda" style="width:290px;">
                                <img src="<?php echo $link1[2] ?>" class="card-img-top" height="210px" loading='lazy' alt="...">
                                </a>
                                <div class="card-body cartao bg-dark" style="padding:10px;">
                                    <p style="text-align:justify; max-height:140px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white "><?php echo $descjogo[2] ?></p>
                                </div>

                            </div>
                            <a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo1[3] ?>">
                                <div class="card text-white bg-dark" id="card-foda" style="width:290px;">
                                    <img src="<?php echo $link1[3] ?>" class="card-img-top" height="210px" loading='lazy' alt="...">
                                    </a>
                                    <div class="card-body cartao    bg-dark" style="padding:10px;">
                                        <p style="text-align:justify; max-height:140px; overflow:auto; display:flex; padding-right:5px;" class="card-text b text-white customScroll"><?php echo $descjogo[3] ?></p>
                                    </div>


                                </div>
                                <a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo1[4] ?>">
                                    <div class="card text-white bg-dark" id="card-foda" style="width:290px;">
                                        <img src="<?php echo $link1[4] ?>"  class="card-img-top" height="210px" loading='lazy' alt="...">
                                        </a>
                                        <div class="card-body cartao bg-dark" style="padding:10px;">
                                            <p style="text-align:justify; max-height:140px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white"><?php echo $descjogo[4] ?></p>
                                        </div>
                                    </div>
                                    <a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo1[5] ?>">
                                        <div class="card text-white bg-dark" id="card-foda" style="width:290px;">
                                            <img src="<?php echo $link1[5] ?>"  class="card-img-top" height="210px" loading='lazy' alt="...">
                                            </a>
                                            <div class="card-body cartao bg-dark" style="padding:10px;">
                                                <p style="text-align:justify; max-height:140px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white"><?php echo $descjogo[5] ?></p>
                                            </div>
                                        </div>

                                        <a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo1[6] ?>">
                                            <div class="card text-white bg-dark" id="card-foda" style="width:290px;">
                                                <img src="<?php echo $link1[6] ?>"  class="card-img-top" height="210px" loading='lazy' alt="...">
                                                </a>
                                                <div class="card-body cartao bg-dark" style="padding:10px;">
                                                    <p style="text-align:justify; max-height:140px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white"><?php echo $descjogo[6] ?></p>
                                                </div>
                                            </div>

                                            <a href="jogo_mostrar.php?id_jogo1=<?php echo $id_jogo1[7] ?>">
                                                <div class="card text-white bg-dark" id="card-foda" style="width:290px;">
                                                    <img src="<?php echo $link1[7] ?>"  class="card-img-top" height="210px" loading='lazy' alt="...">
                                                    </a>
                                                    <div class="card-body cartao bg-dark" style="padding:10px;">
                                                        <p style="text-align:justify; max-height:120px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white"><?php echo $descjogo[7] ?></p>
                                                    </div>
                                                </div>


                                                </div>
                                                </div>
                                                <p></p>
                                                </div>



                                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous" defer></script>
                                                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous" async></script>
                                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" async></script>
                                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous" defer></script>
                                                </body>
                                                </html>
