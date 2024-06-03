<?php

session_start();

if ((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true)) {
    session_unset();
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.all.min.js'></script>"
    . "<link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css' rel='stylesheet'>"
    . "<link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' rel='stylesheet'>";
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
    echo "<style>
            .custom-swal-popup {
                font-family: 'Poppins', sans-serif !important;
               /* Adiciona espaçamento entre as letras */
            }
            
          </style>";
    exit; // Certifique-se de parar a execução do script após redirecionar
}




require('conecta.php');
if (!isset($_POST['id_review'])) {
    echo "<script>
                window.location.href = 'jogos_recentes.php';
                </script>";
    exit; 
}
$logado = $_SESSION['login'];
$id_review = $_POST['id_review'];



if (isset($_POST['delete'])) {
    $conf = true;
} else {
    echo "<script>
                window.location.href = 'jogos_recentes.php';
                </script>";
    exit; 
}

if ($conf) {
    if (isset($_POST['id_usuario'])) {
        $id_usuario = $_POST['id_usuario'];
    } else {
        $id_usuario = $_SESSION['id_usuario'];
        
    }
    if (isset($_POST['validar'])) {
        $id_jogo=$_POST['jogo_excluir'];
    } else {
        if (isset($_SESSION['id_jogo'])) {
            $id_jogo = $_SESSION['id_jogo'];
        } else {
            $id_jogo = $_POST['jogo_excluir'];
        }
    }

    
    $sql_delete_review = "DELETE FROM reviews WHERE id_review=$id_review";
    if (!$conecta->query($sql_delete_review)) {
        echo "Erro ao apagar o registro: " . $conecta->error . "<br>";
    }

    
    $sql_delete_avaliacao = "DELETE FROM avaliacao WHERE id_review=$id_review";
    if (!$conecta->query($sql_delete_avaliacao)) {
        echo "Erro ao apagar o registro: " . $conecta->error . "<br>";
    }

    
    $sql_count_reviews = "SELECT COUNT(*) AS num_reviews FROM reviews WHERE id_usuario='$id_usuario'";
    $resultado_count_reviews = $conecta->query($sql_count_reviews);
    if ($resultado_count_reviews->num_rows > 0) {
        $linha_count_reviews = $resultado_count_reviews->fetch_assoc();
        $quant_reviews = $linha_count_reviews['num_reviews'];
    } else {
        $quant_reviews = 0;
    }

    
    $sql_update_quant_reviews = "UPDATE usuario SET quant_reviews = '$quant_reviews' WHERE id_usuario = '$id_usuario'";
    if (!$conecta->query($sql_update_quant_reviews)) {
        echo "Erro ao atualizar a quantidade de reviews do usuário: " . $conecta->error;
    }
    $sql_avaliacao_media = "SELECT AVG(avaliacao_total) as avaliacao_media FROM avaliacao WHERE id_jogo = '$id_jogo'";
    $resultado_avaliacao_media = $conecta->query($sql_avaliacao_media);
    if ($resultado_avaliacao_media->num_rows > 0) {
        $row_avaliacao_media = $resultado_avaliacao_media->fetch_assoc();
        $avaliacao_media = (int) $row_avaliacao_media['avaliacao_media'];

        
        $sql_update_avaliacao_media = "UPDATE games SET avaliacao_media= '$avaliacao_media' WHERE id_jogo=$id_jogo";
        if (!$conecta->query($sql_update_avaliacao_media)) {
            echo "Erro ao atualizar a avaliação média do jogo: " . $conecta->error . "<br>";
        }
    }

    
    echo "<script>
         window.location.href = 'jogo_mostrar.php?id_jogo1=$id_jogo';
      </script>";

    exit; 
    unset($_POST['validar']);
}


$conecta->close();
?>
