<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        session_start();
        $nomecad = isset($_POST['nomecad']) ? $_POST['nomecad'] : '';
        $senhacad = ($_POST['senhacad']);
        $emailcad = $_POST['emailcad'];
        $nome_servidor = "localhost";
        $nome_usuario = "root";
        $senhabanco = "";
        $banco = "db_review";

        $conecta = new mysqli($nome_servidor, $nome_usuario, $senhabanco, $banco);
        if ($conecta->connect_error) {
            die("Conexão falhou: " . $conecta->connect_error . "<br>");
        } else {
            
        }

        function inserirDados($nomecad, $emailcad, $senhacad) {

            $ok = true;
            $erro1 = false;
            $nome_servidor = "localhost";
            $nome_usuario = "root";
            $senhabanco = "";
            $banco = "db_review";
            $conecta = new mysqli($nome_servidor, $nome_usuario, $senhabanco, $banco);
            if ($conecta->connect_error) {
                die("Conexão falhou: " . $conecta->connect_error . "<br>");
            } else {
                
            }
            $tenta_achar = "SELECT * FROM usuario WHERE email='$emailcad' OR nome_usuario='$nomecad'";
            $resultado = $conecta->query($tenta_achar);
            if ($resultado->num_rows > 0) {
                $erro1 = true;
                $_SESSION['erro1'] = true;
                echo "<script> 
                window.location.href = 'cadastro.php';
            </script>";
                $ok = false;
            } else {
                if (!filter_var($emailcad, FILTER_VALIDATE_EMAIL)) {
                    $ok = false;
                }
                if ((!isset($nomecad) or empty($nomecad)) or strlen($nomecad) <=3 or strlen($nomecad) >20) {
                    
                    
                    echo "<script> 
                window.location.href = 'cadastro.php';
            </script>";$ok = false;
                }
                if (!isset($emailcad) or empty($emailcad)) {
                    $ok = false;
                }
                if (!isset($senhacad) or empty($senhacad) or strlen($senhacad) <= 8) {
 
                    echo "<script> 
                window.location.href = 'cadastro.php';
            </script>";
                    $ok = false;
                }
                if ($ok) {
                    date_default_timezone_set('America/Sao_Paulo');
                        $tempo = time();
                        $horarioatual = date("Y-m-d H:i:s", $tempo);
                    $sql = "INSERT INTO usuario (nome_usuario, email, senha, horario_criacao)
                   VALUES ('$nomecad', '$emailcad', '" . md5($senhacad) . "', '$horarioatual')";
                    if ($conecta->query($sql) === TRUE) {
                        $login = $emailcad;
                        $senha = $senhacad;
                        header('location:login.php');
                        $ok = true;
                        
                    } else {
                        $ok = false;
                    }
                    
                }
            }
        }

        $conecta->close();

        inserirDados($nomecad, $emailcad, $senhacad);
        ?>
    </body>

</html>