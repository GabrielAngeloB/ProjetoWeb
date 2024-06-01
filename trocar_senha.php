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
    <title>Meu Perfil</title>
    <link rel="icon" href="https://static.thenounproject.com/png/122214-200.png">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        
    </head>
    <body style="background-color:#242629">
        <nav class="navbar navbar-expand-sm" style="background-color:darkslategrey; z-index:2;">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="float:left">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse flex-grow-0" id="navbarNav">
                    <ul class="navbar-nav me-auto">  
                        <li class="nav-item">
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
        <div class="container descer">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card bg-secondary">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title " style="font-weight:bold; text-align:center;">Segurança</h5>
                        </div>
                        <div class="card-body" style="background-color:gainsboro">
                            <form id="trocaSenhaForm" action="recebe_trocar_senha.php" method="POST">
                                <div class="form-group">
                                    <label for="current_password">Senha Atual:</label>
                                    <div class="input-group">
                                        <input type="password" id="current_password" name="current_password" class="form-control" minlength="8" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('current_password', 'imagem1')">
                                                <img src="imagens/olho_fechado.svg" id="imagem1" alt="Show Password" style="width: 20px; height:25px; object-fit:contain">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="new_password">Nova senha:</label>
                                    <div class="input-group">
                                        <input type="password" id="new_password" name="new_password" class="form-control" minlength="8" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('new_password', 'imagem2')">
                                                <img src="imagens/olho_fechado.svg" id="imagem2" alt="Show Password" style="width: 20px; height:25px; object-fit:contain">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="confirm_new_password">Confirmar nova senha:</label>
                                    <div class="input-group">
                                        <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" minlength="8" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('confirm_new_password', 'imagem3')">
                                                <img src="imagens/olho_fechado.svg" id="imagem3" alt="Show Password" style="width: 20px; height:25px; object-fit:contain">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <ul>
                                    <li class="alert alert-primary" role="alert" style="font-size:16px; position:relative; font-style:italic; right:30px; width:97%; padding-top:5px; padding-bottom:5px;"><span style="font-weight:bold;">Aviso:</span> Mínimo de 8 caracteres!</li>
                                    
                                </ul>
                                <button type="button" class="btn btn-primary d-flex mx-auto" data-bs-toggle="modal" data-bs-target="#confirmModal">Trocar Senha</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Alterações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja mudar sua senha?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  style="font-weight:bold;">Cancelar</button>
                <button type="button" id="confirmButton" class="btn btn-success" style="font-weight:bold;">Confirmar</button>
            </div>
        </div>
    </div>
</div>
        <script>
            function togglePassword(inputId, imageId) {
                var input = document.getElementById(inputId);
                var image = document.getElementById(imageId);
                if (input.type === "password") {
                    input.type = "text";
                    image.src = "imagens/olho_aberto.svg";
                } else {
                    input.type = "password";
                    image.src = "imagens/olho_fechado.svg";
                }
            }
            $(document).ready(function () {
                $('input[type="password"]').on('input', function () {
                    var minLength = 8; // Mínimo de caracteres para a senha
                    var $input = $(this);

                    // Verifica o comprimento da senha enquanto o usuário digita
                    if ($input.val().length < minLength) {
                        $input.addClass('invalid-input'); // Aplica a classe para indicar um erro
                        $input.removeClass('valid-input');
                    } else {
                        $input.removeClass('invalid-input');
                        $input.addClass('valid-input');
                    }
                });

                // Evento para submeter o formulário quando o botão "Confirmar" no modal for clicado
                $('#confirmButton').on('click', function() {
                    $('#trocaSenhaForm').submit();
                });
            });
        </script>

         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </body>
</html>
