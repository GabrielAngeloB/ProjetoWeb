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
        
        <br>
        <div class="fadeInFromBottom">
            <div class="main" style="z-index:-1; margin-top:60px;">
                <div id="meu-carrossel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://blog.ebaconline.com.br/blog/wp-content/uploads/2022/12/kcnb4lkkucfipej3ngqxm-scaled-e1674744655926.jpeg" height="60%" width="90%" class="d-block mx-auto"  style="border-style: solid; border-width: 4px; border-image:linear-gradient(grey, black) 50;" alt="Imagem 1">
                            <div class="carousel-caption">
                                <h5 class="carousel-titulo" style="color:white; text-shadow: 0 0 3px #000000, 0 0 5px #000000; font-size:40px;">Dragons Dogma 2™</h5><br>
                                <a href="cadastro.php">
                                    <button class="btn btn-primary" >Saiba mais</button>
                                </a>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://www.einerd.com.br/wp-content/uploads/2017/03/photodune-9235903-game-m-16x91.jpg" height="60%" width="90%" class="d-block mx-auto" style="border-style: solid; border-width: 4px; border-image:linear-gradient(grey, black) 50;" alt="Imagem 2">
                            <div class="carousel-caption">
                                <h5 class="carousel-titulo" style="color:white; text-shadow: 0 0 3px #000000, 0 0 5px #000000; font-size:40px;">Dragon Age Inquisition™</h5><br>
                                <a href="cadastro.php">
                                    <button class="btn btn-primary" >Saiba mais</button>
                                </a>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://i0.wp.com/jornal.usp.br/wp-content/uploads/2022/06/202205601_jogos_streaming.jpg?resize=1200%2C630&ssl=1" height="60%" width="90%" class="d-block mx-auto" style="border-style: solid; border-width: 4px; border-image:linear-gradient(grey, black) 50;" alt="Imagem 3">
                            <div class="carousel-caption">
                                <h5 class="carousel-titulo" style="color:white; text-shadow: 0 0 3px #000000, 0 0 5px #000000; font-size:40px;">Far cry 5™</h5><br>
                                <a href="cadastro.php">
                                    <button class="btn btn-primary" >Saiba mais</button>
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
                    <div class="card text-white bg-dark" id="card-foda" style="">
                        <img src="https://t.ctcdn.com.br/CeRsoL0J56PIBIDxDEIiDoPutoA=/640x360/smart/i845435.jpeg" class="card-img-top" height="210px" alt="...">
                        <div class="card-body cartao bg-dark" style="padding:10px;">
                            <p style="text-align:justify; max-height:120px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white">Desbravando selvas exuberantes, enfrentando tribos misteriosas e desvendando segredos ancestrais. Embarque em uma aventura inesquecível e viva a emoção da descoberta!</p>
                        </div>
                    </div>
                    <div class="card text-white bg-dark" id="card-foda" ">
                        <img src="https://www.mobiletime.com.br/wp-content/uploads/2023/10/jogodotigre.jpeg" height="210px" class="card-img-top" alt="...">
                        <div class="card-body cartao bg-dark" style="padding:10px;">
                            <p style="text-align:justify; max-height:120px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white">Exercite sua mente com puzzles intrigantes, desbloqueie novos níveis e celebre cada conquista. Descubra um mundo de desafios e explore o poder da lógica!</p>
                        </div>
                    </div>
                    <a href="https://google.com">
                        <div class="card text-white bg-dark" id="card-foda">
                            <img src="https://s2-techtudo.glbimg.com/PjUfWhWzVF0YaCtMFktp9J-Qmyg=/0x0:644x456/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_08fbf48bc0524877943fe86e43087e7a/internal_photos/bs/2021/O/k/5MO0i7TsyygbBxZCAVwg/2013-04-16-ddtank.jpg" height="210px" class="card-img-top" alt="...">
                            <div class="card-body cartao bg-dark" style="padding:10px;">
                                <p style="text-align:justify; max-height:120px; overflow:auto; display:flex; padding-right:5px;" class="card-text text-white ">Adrenalina a mil em disputas acirradas com jogadores de todo o mundo. Suba no ranking, domine as estratégias e seja o campeão supremo!</p>
                            </div>
                    </a>
                </div>
                <div class="card text-white bg-dark" id="card-foda">
                    <img src="https://img.odcdn.com.br/wp-content/uploads/2023/08/baldurs-gate-3.jpg" class="card-img-top" height="210px" alt="...">
                    <div class="card-body cartao    bg-dark" style="padding:10px;">
                        <p style="text-align:justify; max-height:120px; overflow:auto; display:flex; padding-right:5px;" class="card-text b text-white customScroll">Mergulhe em universos fantásticos, explore diferentes estilos e crie suas próprias histórias. Experimente a alegria de jogar e deixe sua imaginação correr solta!</p>
                    </div>
                </div>
            </div>
        </div>
        <p></p>
    </div>
        
        
        





     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
