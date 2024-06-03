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
if ($_SESSION['login'] !== 'gabridestiny@hotmail.com') {
    echo "<script>
                window.location.href = 'index.php';
                </script>";
}
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
    <title>Adicionar Jogos</title>
    <link rel="icon" href="https://static.thenounproject.com/png/122214-200.png">
    <head>
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
        <form  class="mx-auto adicjogos" action="recebe_jogos.php" enctype="multipart/form-data" method="POST">
            <div class="form-group">
                <label style="padding-bottom:5px;" for="nomne_jogo">Nome do jogo:</label>
                <input type="text" class="form-control" id="nome_jogo" placeholder="Nome do jogo" name="nome_jogo" required><p></p>
            </div>
            <div class="form-group">
                <label style="padding-bottom:5px;" for="publisher">Publisher do jogo:</label>
                <input type="text" class="form-control" id="publisher" placeholder="Publisher" name="publisher" required><p></p>
            </div>
            <div class="form-group">
                <label style="padding-bottom:5px;" for="email">Desenvolvedora:</label>
                <input type="text" class="form-control" id="desenvolvedora" placeholder="Desenvolvedora" name="dev" required><p></p>
            </div>
            <label style="padding-bottom:5px;" for="start">Data de lancamento:</label><br>
            <input type="date" id="start" name="data_lancamento" class="form-control" value="2018-07-22" required/>
            <div class="form-group">
                <label for="generos" style="padding-bottom:5px;" >Generos:</label>
                <select multiple class="form-control select-picker" name="generos[]" id="generos" size="8" required>
                    <option>MOBA</option>
                    <option>Point-and-click</option>
                    <option>Luta</option>
                    <option>Shooter</option>
                    <option>Música</option>
                    <option>Plataforma</option>
                    <option>Quebra-Cabeça</option>
                    <option>Corrida</option>
                    <option>Estratégia em tempo Real</option>
                    <option>RPG</option>
                    <option>Simulação</option>
                    <option>Esporte</option>
                    <option>Estratégia</option>
                    <option>Estratégia em turnos</option>
                    <option>Tático</option>
                    <option>Hack and slash/Beat 'em up</option>
                    <option>Quiz/Trivia</option>
                    <option>Pinball</option>
                    <option>Aventura</option>
                    <option>Indie</option>
                    <option>Arcade</option>
                    <option>Visual Novel</option>
                    <option>Jogo de carta/tabuleiro</option>
                </select>
                <p></p>

            </div>
            <div class="form-group">
                <label style="padding-bottom:5px;" for="descricao">Descrição do jogo:</label>
                <textarea class="form-control" id="descricao" rows="4" placeholder='Descricao do jogo' name="desc_jogo" required></textarea>
            </div>
            <div class="mb-3"> 
                <label style="padding-bottom:5px;" for="imagem" class="form-label"> Imagem do jogo:</label> 
                <input type="file" class="form-control" id="imagem" name="imagem" required> 
            </div> 
            <button style="padding-top:5px;" type="submit" class="btn btn-primary">Enviar informações</button>

        </form>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </body>

</html>
