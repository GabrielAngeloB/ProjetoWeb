<?php
session_start();

// Verifica se os dados do formulário foram submetidos
if (!isset($_POST['nomecad']) || !isset($_POST['emailcad']) || !isset($_POST['senhacad'])) {
    echo "<script>window.location.href = 'cadastro.php';</script>";
    exit();
}

$nomecad = $_POST['nomecad'];
$senhacad = $_POST['senhacad'];
$emailcad = $_POST['emailcad'];
require ('conecta.php');

function inserirDados($nomecad, $emailcad, $senhacad) {
    $ok = true;
    $erro1 = false;
    require ('conecta.php');

    $tenta_achar = "SELECT * FROM usuario WHERE email='$emailcad' OR nome_usuario='$nomecad'";
    $resultado = $conecta->query($tenta_achar);
    
    if ($resultado->num_rows > 0) {
        $erro1 = true;
        $_SESSION['erro1'] = true;
        echo "<script>window.location.href = 'cadastro.php';</script>";
        $ok = false;
    } else {
        if (!filter_var($emailcad, FILTER_VALIDATE_EMAIL)) {
            $ok = false;
        }
        if ((!isset($nomecad) || empty($nomecad))) {
            echo "<script>window.location.href = 'cadastro.php';</script>";
            $ok = false;
        }
        if (!isset($emailcad) || empty($emailcad)) {
            $ok = false;
        }
        if (!isset($senhacad) || empty($senhacad) || strlen($senhacad) < 8) {
            echo "<script>window.location.href = 'cadastro.php';</script>";
            $ok = false;
        }

        if ($ok) {
            date_default_timezone_set('America/Sao_Paulo');
            $tempo = time();
            $horarioatual = date("Y-m-d H:i:s", $tempo);

            $sql = "INSERT INTO usuario (nome_usuario, email, senha, horario_criacao)
                    VALUES ('$nomecad', '$emailcad', '" . md5($senhacad) . "', '$horarioatual')";
            
            if ($conecta->query($sql) === TRUE) {
                echo " <link href='css2/estilos.css' type='text/css' rel='stylesheet'> <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js'></script>"
            . "<link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css' rel='stylesheet'>"
            . "<link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' rel='stylesheet'>";
            echo "<script>
                    window.onload = function() {
                        document.body.style.backgroundColor = '#37363d';
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Cadastro realizado com sucesso!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'custom-swal-popup'
                            },
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'login.php';
                            }
                        });
                    }
                  </script>";
            echo "<style>
                    .custom-swal-popup {
                        font-family: 'Poppins', sans-serif !important;
               /* Adiciona espaçamento entre as letras */
            }
            
                  </style>";
                
                $ok = true;
            } else {
                echo "<link href='css2/estilos.css' type='text/css' rel='stylesheet'> "
    . "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js'></script>"
    . "<link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css' rel='stylesheet'>"; // Adiciona o link para o CSS customizado
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                // Altera o background da página
                document.body.style.backgroundColor = '#37363d';
                
                Swal.fire({
                    title: 'Erro!',
                    text: 'Algo deu errado!',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'custom-swal-popup'
                    },
                    allowOutsideClick: false // Evita fechar ao clicar fora do alerta
                }).then((result) => {
                    window.location.href = 'cadastro.php';
                });
            });
          </script>";
    exit; // Certifique-se de parar a execução do script após redirecionar
                $ok = false;
            }
        }
    }
    $conecta->close();
}

inserirDados($nomecad, $emailcad, $senhacad);
?>
