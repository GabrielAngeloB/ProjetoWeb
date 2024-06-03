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

if (isset($_SESSION['erroinfo']) and $_SESSION['erroinfo']) {
    $erro = "<div class='alert alert-danger' role='alert' style=' border: 1px solid red; margin-top:13px; font-weight:bold; padding-top:3px; padding-bottom:3px;'> Email ou usuário já existem!
                            </div>";
} else {
    $erro = "";
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
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
}

$sql = "SELECT * from usuario where id_usuario = $id_usuario";
$resultado = $conecta->query($sql);
if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $username = $linha['nome_usuario'];
        $email = $linha['email'];
        $img_perfil = $linha['img_perfil'];
    }
}
if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
}

$sql = "SELECT * from usuario where id_usuario = $id_usuario";
$resultado = $conecta->query($sql);
if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $username = $linha['nome_usuario'];
        $email = $linha['email'];
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
        

    </head>
    <body style="background-color:#242629">
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Acesso Negado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Esta página só pode ser acessada por usuário logado!.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="login.php" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</div>

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
        <div class="fadeInFromBottom largura"
             <div class="container largura" style='margin:auto; margin-bottom:40px; margin-top:100px;'>
                <h1 class="mb-4"></h1>
                <div class="card no-hover-effect" style="border: 1px solid black;">
                    <div class="card-body" style="border: 3px solid black; border-radius:0.5%; background-color:#484848;">

                        <div class="container mt-2">
                            <h1 class="mb-3" style="text-align:center; font-weight:bold; text-shadow: -1px 0 darkslategrey, 0 1px darkslategrey, 1px 0 darkslategrey, 0 -1px darkslategrey;">
                                <span style="border:2px solid black; padding-right:9px; padding-left:9px; border-radius:10px; background-color:black; color:white">Editar Perfil</span>
                            </h1>
                            <div class="card mb-4 no-hover-effect" style="background-color:gray;">
                                <div class="card-body" style="border:3px solid black; border-radius:0.5%; background-color:#909090; ">

                                    <form id="editProfileForm" method="POST" action="recebe_editar_usuario.php" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="profile_pic"></label>
                                            <div class="thumbnail">
                                                <img src="<?php echo $img_perfil ?>" height="200" width="200" id="profile_pic_preview" class="img-fluid mb-3 d-flex " style="cursor: pointer; margin-left:200px; display:flex; border-radius:50%; margin:auto; border: 2px solid black;  " onclick="document.getElementById('profile_pic').click();" alt="Profile Picture">
                                            </div>
                                            <p id="file_name" style="text-align:center; font-weight:bold;"></p>
                                            <p  style="text-align:center; font-weight:bold; text-decoration:underline">Clique para mudar a foto de perfil.</p>
                                            <input type="file" id="profile_pic" name="profile_pic" class="form-control-file" style="display: none;" onchange="updatePreview(this)">
                                        </div>
                                        <div class="form-group">
                                            <label for="username" size="50" style="font-weight:bold; font-family: 'Arial Black', Gadget, sans-serif; margin-bottom:3px; margin-left:2px;">Nome:</label>
                                            <input type="text" style=" font-style:italic; margin-bottom:5px;"  id="username" name="username" class="form-control" value="<?php echo $username; ?>" required>
                                            <div  id="name-error"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" style="font-weight:bold; font-family: 'Arial Black', Gadget, sans-serif; margin-bottom:3px; margin-left:2px; margin-top:3px">Email:</label>
                                            <input style='font-style:italic; margin-bottom:15px; ' type="email" id="email" name="email" class="form-control"  value="<?php echo $email; ?>"  required>
                                            <div id="email-error"></div>
                                        </div>
                                        <?php
                                        echo $erro;
                                        unset($_SESSION['erroinfo'])
                                        ?>

                                        <button OnClick="return ConfirmDelete()" type="submit" id="submitButton" style="font-weight:bold;" class="btn btn-primary">Salvar</button>
                                        <a href="trocar_senha.php"  class="btn btn-secondary" style="font-weight:bold;">Trocar senha</a>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>










         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
                                                    function updatePreview(input) {
                                                        var file = input.files[0];
                                                        var reader = new FileReader();

                                                        reader.onload = function (e) {
                                                            document.getElementById('profile_pic_preview').src = e.target.result;
                                                        }

                                                        reader.readAsDataURL(file);

                                                        var fileName = file.name;
                                                        document.getElementById('file_name').innerText = fileName;
                                                    }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var nomeInput = document.getElementById('username');
                var emailInput = document.getElementById('email');
                nomeInput.style.border = '2px solid lightgreen';
                emailInput.style.border = '2px solid lightgreen';

                nomeInput.addEventListener('input', function () {
                    var nome = nomeInput.value.trim();
                    if (nome.length < 4 || nome.length > 20) {
                        nomeInput.style.border = '2px solid red';
                    } else {
                        nomeInput.style.border = '2px solid lightgreen';
                    }
                });

                emailInput.addEventListener('input', function () {
                    var email = emailInput.value.trim();
                    // Aqui podemos usar uma expressão regular simples para validar o formato do email
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailRegex.test(email) && email.length < 100) {
                        emailInput.style.border = '2px solid lightgreen';
                    } else {
                        emailInput.style.border = '2px solid red';
                    }
                });
            });

        </script>
        <script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('editProfileForm');
        var usernameInput = document.getElementById("username");
        var emailInput = document.getElementById("email");
        var submitButton = document.getElementById("submitButton");
        var confirmButton = document.getElementById('confirmButton');

        // Função para verificar se os campos estão válidos
        function validateFields() {
            var username = usernameInput.value.trim();
            var email = emailInput.value.trim();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var ok = true; // Variável para controlar se o formulário pode ser enviado

            // Limpa mensagens de erro anteriores
            document.getElementById('name-error').textContent = '';
            document.getElementById('email-error').textContent = '';

            // Verifica o comprimento do nome
            if (username.length < 4 || username.length > 20) {
                $('#name-error').html('<div class="alert alert-danger" style="border: 1px solid red; margin-top:13px; font-weight:bold; padding-top:3px; padding-bottom:3px; margin-bottom:5px;" role="alert">O nome de usuário deverá ter de 4 a 20 caracteres!</div>');
                ok = false;
            }

            // Verifica o email
            if (!emailRegex.test(email) || email.length > 100) {
                $('#email-error').html('<div class="alert alert-danger" style="border: 1px solid red; margin-top:13px; font-weight:bold; padding-top:3px; padding-bottom:3px;" role="alert">Email inválido!</div>');
                ok = false;
            }

            // Retorna se o formulário está válido
            return ok;
        }

        // Adiciona um evento de escuta para monitorar as mudanças nos campos do formulário
        form.addEventListener('input', function () {
            var isValid = validateFields(); // Verifica se os campos são válidos
            submitButton.disabled = !isValid; // Desativa o botão se o formulário não for válido
        });

        // Exibe o modal quando o botão de submit é clicado
        submitButton.addEventListener('click', function (event) {
            event.preventDefault(); // Impede o envio do formulário
            $('#confirmModal').modal('show'); // Exibe o modal
        });

        // Submete o formulário quando o botão de confirmação no modal é clicado
        confirmButton.addEventListener('click', function () {
            form.submit();
        });

        // Adiciona um evento de escuta para o envio do formulário
        form.addEventListener('submit', function (event) {
            var isValid = validateFields(); // Verifica se os campos são válidos

            // Se o formulário não for válido, impede o envio
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Alterações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja alterar seus dados?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  style="font-weight:bold;">Cancelar</button>
                <button type="button" id="confirmButton" class="btn btn-success" style="font-weight:bold;">Confirmar</button>
            </div>
        </div>
    </div>
</div>

    </body>
</html>