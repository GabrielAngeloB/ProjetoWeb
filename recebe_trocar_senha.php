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
require('conecta.php');

if (!isset($_POST['current_password'])) {
    echo "<script>
                window.location.href = 'trocar_senha.php';
                </script>";
}

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha_atual = $_POST['current_password'];
    $senha_nova = $_POST['new_password'];
    $confirmar_senha_nova = $_POST['confirm_new_password'];

    // Verificar tamanho das senhas
    if (strlen($senha_atual) < 8 || strlen($senha_nova) < 8 || strlen($confirmar_senha_nova) < 8) {
        echo " <link href='css2/estilos.css' type='text/css' rel='stylesheet'> 
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js'></script>
        <link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' rel='stylesheet'>";

echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.backgroundColor = '#37363d';
            Swal.fire({
                title: 'Erro!',
                text: 'Mínimo de 8 caracteres!',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-swal-popup'
                },
                allowOutsideClick: false,
                timer: 3000,
                timerProgressBar: true
            }).then((result) => {
                window.location.href = 'trocar_senha.php';
            });

            // Caso o SweetAlert2 seja fechado pelo temporizador, redirecionar para a página trocar_senha.php
            const timerInterval = setInterval(() => {
                if (Swal.getTimerLeft() <= 0) {
                    clearInterval(timerInterval);
                    window.location.href = 'trocar_senha.php';
                }
            }, 100);
        });
      </script>";

echo "<style>
        .custom-swal-popup {
            font-family: 'Poppins', sans-serif !important;
            /* Adiciona espaçamento entre as letras */
        }
      </style>";
exit();
    }

    // Verificar se as novas senhas coincidem
    if ($senha_nova !== $confirmar_senha_nova) {
       echo " <link href='css2/estilos.css' type='text/css' rel='stylesheet'> 
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js'></script>
        <link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' rel='stylesheet'>";

echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.backgroundColor = '#37363d';
            Swal.fire({
                title: 'Erro!',
                text: 'As senhas não coincidem!',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-swal-popup'
                },
                allowOutsideClick: false,
                timer: 3000,
                timerProgressBar: true
            }).then((result) => {
                window.location.href = 'trocar_senha.php';
            });

            // Caso o SweetAlert2 seja fechado pelo temporizador, redirecionar para a página trocar_senha.php
            const timerInterval = setInterval(() => {
                if (Swal.getTimerLeft() <= 0) {
                    clearInterval(timerInterval);
                    window.location.href = 'trocar_senha.php';
                }
            }, 100);
        });
      </script>";

echo "<style>
        .custom-swal-popup {
            font-family: 'Poppins', sans-serif !important;
            /* Adiciona espaçamento entre as letras */
        }
      </style>";
exit();
    }

    $sql = "SELECT * FROM usuario WHERE id_usuario = $id_usuario AND senha = '".md5($senha_atual)."'";
    $resultado = $conecta->query($sql);

    if ($resultado->num_rows > 0) {
    $senha_nova_hashed = md5($senha_nova);
    $sql2 = "UPDATE usuario SET senha = '$senha_nova_hashed' WHERE id_usuario = $id_usuario";
    if ($conecta->query($sql2) === TRUE) {
        echo " <link href='css2/estilos.css' type='text/css' rel='stylesheet'> <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js'></script>"
        . "<link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css' rel='stylesheet'>"
        . "<link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' rel='stylesheet'>";
        echo "<script>
                window.onload = function() {
                    document.body.style.backgroundColor = '#37363d';
                    Swal.fire({
                        title: 'Sucesso!',
                        text: 'Senha atualizada com sucesso!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'custom-swal-popup'
                        },
                        allowOutsideClick: false,
                        timer: 3000,
                        timerProgressBar: true
                    }).then((result) => {
                        window.location.href = 'editar_usuario.php';
                    });

                    const timerInterval = setInterval(() => {
                        if (Swal.getTimerLeft() <= 0) {
                            clearInterval(timerInterval);
                            window.location.href = 'editar_usuario.php';
                        }
                    }, 100);
                }
              </script>";
        echo "<style>
                .custom-swal-popup {
                    font-family: 'Poppins', sans-serif !important;
               /* Adiciona espaçamento entre as letras */
            }
              </style>";
    } else {
        echo " <link href='css2/estilos.css' type='text/css' rel='stylesheet'> <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js'></script>"
        . "<link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css' rel='stylesheet'>"
        . "<link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' rel='stylesheet'>";
        echo "<script>
                window.onload = function() {
                    document.body.style.backgroundColor = '#37363d';
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Erro ao atualizar a senha.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'custom-swal-popup'
                        },
                        allowOutsideClick: false,
                        timer: 3000,
                        timerProgressBar: true
                    }).then((result) => {
                        window.location.href = 'trocar_senha.php';
                    });

                    const timerInterval = setInterval(() => {
                        if (Swal.getTimerLeft() <= 0) {
                            clearInterval(timerInterval);
                            window.location.href = 'trocar_senha.php';
                        }
                    }, 100);
                }
              </script>";
        echo "<style>
                .custom-swal-popup {
                    font-family: 'Poppins', sans-serif !important;
               /* Adiciona espaçamento entre as letras */
            }
              </style>";
    }
} else {
    echo " <link href='css2/estilos.css' type='text/css' rel='stylesheet'> <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js'></script>"
    . "<link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css' rel='stylesheet'>"
    . "<link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' rel='stylesheet'>";
    echo "<script>
            window.onload = function() {
                document.body.style.backgroundColor = '#37363d';
                Swal.fire({
                    title: 'Erro!',
                    text: 'Senha atual incorreta.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'custom-swal-popup'
                    },
                    allowOutsideClick: false,
                    timer: 3000,
                    timerProgressBar: true
                }).then((result) => {
                    window.location.href = 'trocar_senha.php';
                });

                const timerInterval = setInterval(() => {
                    if (Swal.getTimerLeft() <= 0) {
                        clearInterval(timerInterval);
                        window.location.href = 'trocar_senha.php';
                    }
                }, 100);
            }
          </script>";
    echo "<style>
            .custom-swal-popup {
                font-family: 'Poppins', sans-serif !important;
           /* Adiciona espaçamento entre as letras */
        }
          </style>";
}
}
?>
