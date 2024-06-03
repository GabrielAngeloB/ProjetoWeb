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
$logado = $_SESSION['login'];
$adicionar = '';
if ($_SESSION['login'] == 'gabridestiny@hotmail.com') {
    $adicionar = "<a class='dropdown-item' href='adicionar_jogos.php'>Adicionar Jogo</a>";
}
require ('conecta.php');

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
}

$stmt = $conecta->prepare("SELECT img_perfil FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $img_perfil = $linha['img_perfil'];
    }
}
$stmt->close();

$pesquisa = isset($_GET["pesquisa"]) ? $_GET["pesquisa"] : "";
$genero = isset($_GET["genero"]) ? $conecta->real_escape_string($_GET["genero"]) : "";
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css2/estilos.css">
        <title>Lista de Jogos</title>
        <link rel="icon" href="https://static.thenounproject.com/png/122214-200.png">
        <style>
            .card:hover {
                box-shadow: 0 0px 12px 0 black;
            }
            .dropdown-toggle:hover {
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
                transition: box-shadow 0.3s ease-in-out;
            }
            .dropdown-menu {
                transition: opacity 0.3s ease-in-out;
            }
        </style>
        <script>
            function selecionarGenero(genero) {
                document.getElementById('genero').value = genero;
                document.getElementById('formulario').submit();
            }
        </script>
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
                                <li><a class="dropdown-item" href="jogos_recentes.php">Jogos recentes</a></li>
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
        <form method="GET" id="formulario">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Barra de pesquisa principal -->
                        <div class="d-flex justify-content-center">
                            <div class="searchbar">
                                <input class="search_input" style="background-color:#353b48" type="text" size="80%" name="pesquisa" value="<?php echo $pesquisa ?>" placeholder="Pesquisar...">
                                <a href="#" class="search_icon" onclick="submitForm();"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Dropdown de seleção de gênero -->
                        <div class="dropdown d-flex justify-content-center" style="margin-bottom: 20px; position:relative; bottom:6px;">
                            <button class="btn btn-secondary dropdown-toggle genero-btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #2c3e50; color: white; opacity: 0.8;">
                                Selecionar gênero
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background-color: #2c3e50; border: none;">
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('MOBA')">MOBA</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Point-and-click')">Point-and-click</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Luta')">Luta</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Shooter')">Shooter</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Música')">Música</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Plataforma')">Plataforma</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Quebra-Cabeça')">Quebra-Cabeça</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Corrida')">Corrida</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Estratégia em tempo Real')">Estratégia em tempo Real</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('RPG')">RPG</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Simulação')">Simulação</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Esporte')">Esporte</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Estratégia')">Estratégia</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Estratégia em turnos')">Estratégia em turnos</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Tático')">Tático</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Hack and slash/Beat \'em up')">Hack and slash/Beat 'em up</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Quiz/Trivia')">Quiz/Trivia</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Pinball')">Pinball</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Aventura')">Aventura</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Indie')">Indie</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Arcade')">Arcade</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Visual Novel')">Visual Novel</a></li>
                                <li><a class="dropdown-item" href="#" style="color: white;" onclick="selecionarGenero('Jogo de carta/tabuleiro')">Jogo de carta/tabuleiro</a></li>
                            </ul>
                        </div>
                        <?php
                        $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
                        $por_pagina = 20;
                        $inicio = ($pagina - 1) * $por_pagina;

                        $pesquisa = $conecta->real_escape_string($pesquisa);
                        $genero = $conecta->real_escape_string($genero);

                        $sql_base = "FROM games WHERE (nome_jogo LIKE ? OR desc_jogo LIKE ? OR generos LIKE ?)";
                        $params = ["%$pesquisa%", "%$pesquisa%", "%$pesquisa%"];

                        if ($genero !== "") {
                            $sql_base .= " AND generos LIKE ?";
                            $params[] = "%$genero%";
                        }

// Contar total de resultados
                        $sql_count = "SELECT COUNT(*) as total_count $sql_base";
                        $stmt = $conecta->prepare($sql_count);
                        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $total_jogos = $result->fetch_assoc()['total_count'];
                        $total_paginas = ceil($total_jogos / $por_pagina);

// Verificar se a página é válida
                        

// Consulta paginada
                        $sql_paginated = "SELECT img_jogo, generos, desc_jogo, nome_jogo, id_jogo, horario_postado, avaliacao_media $sql_base ORDER BY id_jogo ASC LIMIT ?, ?";
                        $params_paginated = array_merge($params, [$inicio, $por_pagina]);

                        $stmt = $conecta->prepare($sql_paginated);
                        $stmt->bind_param(str_repeat('s', count($params)) . 'ii', ...$params_paginated);
                        $stmt->execute();
                        $resultado = $stmt->get_result();

                        $cont = 0;
                        $mensagem = [];
                        while ($linha = $resultado->fetch_assoc()) {
                            $link[$cont] = $linha["img_jogo"];
                            $generos[$cont] = $linha["generos"];
                            $desc[$cont] = $linha["desc_jogo"];
                            $nomejogo[$cont] = $linha["nome_jogo"];
                            $id_jogo[$cont] = $linha["id_jogo"];
                            $horario_jogo[$cont] = $linha['horario_postado'];
                            $avaliacao_media[$cont] = $linha['avaliacao_media'];
                            $cont += 1;

                            // Calcular tempo passado
                            $postedTimeUnix = strtotime($linha['horario_postado']);
                            $currentTimeUnix = time();
                            $timeDiffSeconds = $currentTimeUnix - $postedTimeUnix;
                            $minutos = floor($timeDiffSeconds / 60);
                            $horas = floor($minutos / 60);
                            $dias = floor($horas / 24);

                            if ($minutos < 1) {
                                $mensagem[] = "agora!";
                            } else if ($minutos < 60) {
                                $mensagem[] = "$minutos minuto(s) atrás!";
                            } else if ($horas < 24) {
                                $mensagem[] = "$horas hora(s) atrás!";
                            } else {
                                $mensagem[] = "$dias dia(s) atrás!";
                            }
                        }
                        ?>
                        <?php if ($pesquisa != "" || $genero != ""): ?>
                            <?php if ($total_jogos > 0): ?>
                                <p style="color:white; font-size:25px; text-align:center; font-weight:bold;font-style:italic;">
                                    Sua pesquisa retornou <span style="text-decoration:underline"><?php echo $total_jogos ?></span> resultado(s)
                                </p>
                            <?php else: ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <p style="color:white; font-size:25px; text-align:center; font-weight:bold;font-style:italic;">
                                Temos atualmente <span style="text-decoration:underline"><?php echo $total_jogos ?></span> jogos registrados
                            </p>
                        <?php endif; ?>
                        <input type="hidden" name="genero" id="genero">
                    </div>
                </div>
            </div>
        </form>

        <script>
            function submitForm() {
                // Verifica se algum gênero foi selecionado
                var generoSelecionado = document.getElementById('genero').value;

                // Se nenhum gênero foi selecionado, define o valor do campo de gênero como nulo
                if (!generoSelecionado) {
                    document.getElementById('genero').value = "";
                }

                // Envia o formulário
                document.getElementById('formulario').submit();
            }

            function selecionarGenero(genero) {
                document.getElementById('genero').value = genero;
                var generoBtn = document.querySelector('.genero-btn');
                generoBtn.innerText = genero;
            }
        </script>

        <?php
        if ($total_jogos > 0) {
        for ($j = 0;
                $j < sizeOf($id_jogo);
                $j++) {
            ($j % 2 == 0) ? ($fade = "fadeInFromRight") : ($fade = "fadeInFromLeft");
            if ($j == 0 and $cont > 0) {
                $melhor1[$j] = "<div class='card mb-3 mx-auto responsivo $fade'>
  <div class='row g-0'>
    <div class='col-md-4'>
    <a href='jogo_mostrar.php?id_jogo1=$id_jogo[$j]'>
      <img src='" . "$link[$j]" . "' loading='lazy' class='img-fluid imagem1' style='width:100%; height:100%; max-height:220px; object-fit: fill;'alt='...' >
    </a>
</div>
    <div class='col-md-8 d-flex'>
      <div class='card-body' style='background-color:#9B9CA6; max-height:220px; overflow:auto;'>
        <h5 class='card-title container-fluid' style='font-weight:bold; font-size:23px; text-align:center; text-decoration:underline;'>$nomejogo[$j]</h5>
         <p class='card-text container-fluid' style='flex-grow: 1;object-fit:fill; text-align:justify;'>$desc[$j]<br><span style='font-weight:bold'>Generos</span>: $generos[$j].<br> <span style='font-weight:bold'>Postado</span>: $mensagem[$j]</p>
        <p class='rating-box mx-auto' style='display:flex; justify-content:center; max-width:180px; font-size:20px; border:2px solid gainsboro;  background-color:black;'>Nota média: <span class='mx-auto' style=' text-decoration: underline; color:white; display:flex; justify-content:center; font-size:20px; background-color:black; padding-left:3px; padding-right:1px; border-radius:20%; text-decoration:underline; '>" . $avaliacao_media[$j] . "</span></p></h5>
        <p style='display:flex; justify-content:right; overflow:auto;' class='card-text container-fluid'><small class='text-body-secondary' style='display:flex; justify-content:flex-end'>
                <form action='jogo_mostrar.php' method='get'>
  <input type='hidden' name='id_jogo1' value='$id_jogo[$j]'>
  <button type='submit' class='btn btn-primary mx-auto vermais' style='font-weight:bold; font-style:italic;  text-shadow: 2px 2px #000; font-size:18px; margin:-13px; background-color:darkslategrey; border:1px solid darkslategrey;'>Ver detalhes</button>
</form></small></p>
      </div>
    </div>
  </div>
</div>";
            } else {
                $melhor1[$j] = "<div class='card mb-3 mx-auto responsivo $fade' style='margin-top:40px; margin-bottom:10px;'>
  <div class='row g-0'>
    <div class='col-md-4'>
    <a href='jogo_mostrar.php?id_jogo1=$id_jogo[$j]'>
      <img src='" . "$link[$j]" . "' loading='lazy' class='img-fluid imagem1' style='width:100%; height:100%; max-height:220px; object-fit: fill;'alt='...'>
    </a>
</div>
    <div class='col-md-8 d-flex'>
      <div class='card-body' style='background-color:#9B9CA6; max-height:220px; overflow:auto;'>
        <h5 class='card-title container-fluid' style='font-weight:bold; font-size:23px; text-align:center; text-decoration:underline;' >$nomejogo[$j]</h5>
        <p class='card-text container-fluid' style='flex-grow: 1;object-fit:fill; text-align:justify;'>$desc[$j]<br><span style='font-weight:bold'>Generos:</span> $generos[$j].<br> <span style='font-weight:bold'>Postado</span>: $mensagem[$j]</p>
            
        <p class='rating-box mx-auto' style='display:flex; justify-content:center; font-size:20px; max-width:180px; border:2px solid gainsboro;  background-color:black;'>Nota média: <span class='mx-auto' style='text-decoration: underline; color:white; display:flex; justify-content:center; font-size:20px; background-color:black; padding-left:3px; padding-right:1px; border-radius:20%;'>" . $avaliacao_media[$j] . "<span></p></h5>
            
        <p style='display:flex; justify-content:right; overflow:auto;' class='card-text container-fluid'><small class='text-body-secondary' style='display:flex; justify-content:flex-end'>
                <form action='jogo_mostrar.php' method='get'>
  <input type='hidden' name='id_jogo1' value='$id_jogo[$j]'>
  <button type='submit' class='btn btn-primary mx-auto vermais' style='font-weight:bold; font-style:italic;  text-shadow: 2px 2px #000; font-size:18px; margin:-13px; background-color:darkslategrey; border:1px solid darkslategrey;'>Ver detalhes</button>
</form></small></p>
      </div>
    </div>
  </div>
            </div>";
            }
        }
        ?>
        <?php
        if (isset($id_jogo)) {
            for ($i = 0; $i < sizeOf($id_jogo); $i++) {
                ($i % 2 == 0) ? ($fade = "fadeInFromRight") : ($fade = "fadeInFromLeft");
                echo $melhor1[$i];
            }
        } else {
            $nada = true;
        }
        }else {
            $nada = true;
        }
        ?>
        <div style="text-align:center;">
    <ul class="pagination justify-content-center">
        <?php
        // Número total de páginas
        $total_paginas = ceil($total_jogos / $por_pagina);

        if ($total_paginas > 1) {
            // Número de links visíveis
            $max_links = 10;

            // Calcular o início e o fim da faixa de páginas a serem exibidas
            $start = max(1, $pagina - floor($max_links / 2));
            $end = min($total_paginas, $start + $max_links - 1);

            // Ajustar a faixa se o número de links exceder o total de páginas
            if ($end - $start + 1 < $max_links) {
                $start = max(1, $end - $max_links + 1);
            }

            // Link "Anterior" se aplicável
            if ($pagina > 1) {
                echo '<li class="page-item"><a class="custom-page-link2" style="color: white; bottom:1%;" href="?pagina=' . ($pagina - 1) . '&pesquisa=' . urlencode($pesquisa) . '&genero=' . urlencode($genero) . '">&laquo;</a></li>';
            }

            // Exibir os números das páginas
            for ($i = $start; $i <= $end; $i++) {
                $active = ($i == $pagina) ? 'active' : '';
                $background = ($i == $pagina) ? '#343a40' : '#f8f9fa'; // cinza claro para não ativo
                echo '<li class="page-item"><a class="page-link ' . $active . '" style="font-size:30px; border: 2px solid black; color:black; border-radius:20px; margin-left:5px; padding-right:3px; padding-left:3px; background-color: ' . $background . ';" href="?pagina=' . $i . '&pesquisa=' . urlencode($pesquisa) . '&genero=' . urlencode($genero) . '">' . $i . '</a></li>';
            }

            // Link "Próximo" se aplicável
            if ($pagina < $total_paginas) {
                echo '<li class="page-item"><a class="custom-page-link2" style="color: white; bottom:1%;" href="?pagina=' . ($pagina + 1) . '&pesquisa=' . urlencode($pesquisa) . '&genero=' . urlencode($genero) . '">&raquo;</a></li>';
            }
        }
        ?>
    </ul>
</div>
<?php
if (isset($nada)) {
    echo "<br><div style='text-align:center; margin-top:3%; margin-left:4%;'  id='demoFont'>Desculpe :( <br><p></p> Sua pesquisa retornou 0 resultados.</div> ";
    unset($nada);
}
?>
<br>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous" defer></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous" defer></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous" defer></script>