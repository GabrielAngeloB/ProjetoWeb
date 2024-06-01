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
if (!isset($_GET['id_usuario'])) {
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
    }
} else {
    $id_usuario = $_GET['id_usuario'];
}
if (isset($_SESSION['id_usuario'])) {
    $id_usuario1 = $_SESSION['id_usuario'];
}

$sql = "SELECT img_perfil from usuario where id_usuario = $id_usuario1";
$resultado = $conecta->query($sql);
if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $img_perfil = $linha['img_perfil'];
    }
}
$sql = "SELECT nome_usuario from usuario where id_usuario = $id_usuario";
$resultado = $conecta->query($sql);
if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $nome_usuario = $linha['nome_usuario'];
    }
} else {
    echo "<script>
                window.location.href = 'pagina_nao_encontrada.php';
                </script>";
}

$sql = "SELECT MAX(id_usuario) as id_usuario from usuario";
$resultado = $conecta->query($sql);
if ($resultado->num_rows > 0) {
    while ($linha = $resultado->fetch_assoc()) {
        $id_max = (int) $linha['id_usuario'];
    }
}
if ($id_usuario != $id_usuario1) {
    $titulo = "Reviews de $nome_usuario";
} else {
    $titulo = "Minhas Reviews";
}
?>
<html>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css2/estilos.css">
    <title><?php echo $titulo ?></title>
    <link rel="icon" href="https://static.thenounproject.com/png/122214-200.png">
    <head>
    </head>
    <body style="background-color:#242629">
        <script>
document.addEventListener('DOMContentLoaded', (event) => {
    let reviewIdToDelete = null;
    let jogoIdToDelete = null;
    let usuarioId = '<?php echo $id_usuario; ?>'; // Assuming PHP to pass the user ID

    // Triggered when the modal is about to be shown
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        reviewIdToDelete = button.data('review-id'); // Extract info from data-* attributes
        jogoIdToDelete = button.data('jogo-id'); // Extract info from data-* attributes
    });

    // When the confirm delete button is clicked in the modal
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (reviewIdToDelete !== null && jogoIdToDelete !== null) {
            // Create a form element dynamically
            var form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "deletar_comentario.php");

            // Create input elements
            var idReviewInput = document.createElement("input");
            idReviewInput.setAttribute("type", "hidden");
            idReviewInput.setAttribute("name", "id_review");
            idReviewInput.setAttribute("value", reviewIdToDelete);

            var jogoIdInput = document.createElement("input");
            jogoIdInput.setAttribute("type", "hidden");
            jogoIdInput.setAttribute("name", "jogo_excluir");
            jogoIdInput.setAttribute("value", jogoIdToDelete);

            var validarInput = document.createElement("input");
            validarInput.setAttribute("type", "hidden");
            validarInput.setAttribute("name", "validar");
            validarInput.setAttribute("value", "validar");

            var deleteInput = document.createElement("input");
            deleteInput.setAttribute("type", "hidden");
            deleteInput.setAttribute("name", "delete");
            deleteInput.setAttribute("value", "delete");

            var usuarioIdInput = document.createElement("input");
            usuarioIdInput.setAttribute("type", "hidden");
            usuarioIdInput.setAttribute("name", "id_usuario");
            usuarioIdInput.setAttribute("value", usuarioId);

            // Append inputs to the form
            form.appendChild(idReviewInput);
            form.appendChild(jogoIdInput);
            form.appendChild(validarInput);
            form.appendChild(deleteInput);
            form.appendChild(usuarioIdInput);

            // Append form to the body
            document.body.appendChild(form);

            // Submit the form
            form.submit();
        }
    });
});
</script>


        


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
                            <a class="dropdown-item" href="pagina_usuario.php?id_usuario=<?php echo $id_usuario1; ?>">Ver perfil</a>
                            <a class="dropdown-item" href="editar_usuario.php">Editar perfil</a>
                            <?php echo $adicionar ?>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>


            </div>
        </nav>
        <h1 class="mx-auto letra" style="color:white; margin-top:100px; text-align:center; "><span style="background-color:#343434; padding-left:30px; padding-right:30px; border-radius:10px; text-shadow: 3px 3px black; font-family:monospace; ">⚡︎ ︎<?php echo $titulo ?> ⚡︎</span></h1>
        <div class="fadeInFromBottom">
            <?php
if (!isset($_GET['page'])) {
    $page_number = 1;
} else {
    $page_number = $_GET['page'];
}

$limit = 10;
$initial_page = ($page_number - 1) * $limit;

// Consulta para contar o número total de linhas
$countQuery = "
    SELECT COUNT(*) as total_rows
    FROM reviews
    WHERE id_usuario = $id_usuario
";

$countResult = mysqli_query($conecta, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$total_rows = $countRow['total_rows'];
$total_pages = ceil($total_rows / $limit);

// Use uma única consulta para pegar todas as informações necessárias
$getQuery = "
    SELECT r.id_jogo, r.texto_review, r.id_review, r.horario_review, g.nome_jogo, g.imagem_artwork, a.avaliacao_total
    FROM reviews r
    JOIN games g ON r.id_jogo = g.id_jogo
    LEFT JOIN avaliacao a ON r.id_jogo = a.id_jogo AND r.id_usuario = a.id_usuario
    WHERE r.id_usuario = $id_usuario
    ORDER BY r.id_review DESC
    LIMIT $initial_page, $limit
";

$result = mysqli_query($conecta, $getQuery);

echo "<p style='margin-top:50px;'></p>";

$reviews = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

foreach ($reviews as $index => $review) {
    $review_type = ($review['avaliacao_total'] < 50) ? "negativa" : "positiva";
    
    date_default_timezone_set('America/Sao_Paulo');
    $postedTimeUnix = strtotime($review['horario_review']);
    $currentTimeUnix = time();
    $timeDiffSeconds = $currentTimeUnix - $postedTimeUnix;

    $minutos = floor($timeDiffSeconds / 60);
    $horas = floor($minutos / 60);
    $dias = floor($horas / 24);

    if ($minutos < 1) {
        $mensagem = "agora!";
    } elseif ($minutos < 60) {
        $mensagem = "$minutos minuto(s) atrás!";
    } elseif ($horas < 24) {
        $mensagem = "$horas hora(s) atrás!";
    } else {
        $mensagem = "$dias dia(s) atrás!";
    }
    

    if ($id_usuario != $id_usuario1 and $_SESSION['login'] != "gabridestiny@hotmail.com") {
        $botao = "";
        
    } else {
        $botao = "<button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#confirmDeleteModal' data-review-id='{$review['id_review']}' data-jogo-id='{$review['id_jogo']}' style='padding-top:7px; padding-bottom:5px; font-size:10px; max-height:90px;'>
<span style='text-shadow: 2px 2px black; font-weight:bold; font-size:17px;'>Excluir</span>
</button>";
    }

    echo "<div class='card mx-auto text-center balala2' style='margin-bottom:30px; background-color:#151922; '>
  <div class='card-header' style='font-size:26px; color:white; font-weight:bold; text-decoration:underline; text-shadow: 0px 0px 5px black, 0px 0px 8px black;  '>
    {$review['nome_jogo']}
  </div>
  <div loading='lazy' class='card-body cardBackground2' style='text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white; background-image: url({$review['imagem_artwork']})'>
    <h5 class='card-title' style='font-weight:bold;'>Review $review_type | Nota: <span style='border:1px solid black; padding-left:3px; color:white; background-color:black; padding-right:3px; text-decoration:underline; border-radius:4px; text-shadow: none; '>{$review['avaliacao_total']}</span> </h5>
    <p class='card-text text-center' style='text-align:justify; margin-bottom:18px; font-size:18px; opacity:1; font-style:italic; '>''{$review['texto_review']}''</p>
    <p class='card-text text-center' style='text-align:justify; margin-bottom:10px;'><span style='font-weight:bold;  font-size:17px;' </span>Postado: $mensagem</p>
    <div style='display:flex; justify-content: center; position:relative; top:5px; '>  
      <a href=jogo_mostrar.php?id_jogo1={$review['id_jogo']} class='btn btn-primary' style='margin-right:10px; margin-bottom:16px;'>
        <span style='text-shadow: 2px 2px black; font-weight:bold; font-size:17px;'>Ir para o jogo</span>
      </a>
      <form action='deletar_comentario.php' method='POST' style=''>
        <input type='hidden' name='id_review' value='{$review['id_review']}'>
        <input type='hidden' name='jogo_excluir' value='{$review['id_jogo']}'>
        <input type='hidden' name='validar' value='validar'>
        <input type='hidden' name='id_usuario' value='".$id_usuario."'>
        $botao
      </form>
    </div>
  </div>
</div>";
}
if ($id_usuario == $id_usuario1) {
        $textoReview = "Você não possui reviews!";
    }else {
        $textoReview = "O usuário não possui reviews";
    }
if (sizeOf($reviews) < 1) {
    echo "<div class='card mx-auto balala' style='max-height:80%; margin-top:60px; border:4px solid black; border-radius:10; object-fit:fill;'>
      <div class='card-body cardBackground d-flex flex-column justify-content-center align-items-center'>
        <h2 class='card-title text-center' style='color:white; text-shadow: 1px 1px black; font-weight:bold;'>".$textoReview."</h2>
        <p class='copy card-text text-center' style='color:white; text-shadow: 1px 1px black; font-size:18px; text-decoration:underline;'>Clique abaixo para ver os jogos cadastrados!</p>
        <div class='d-flex justify-content-between'>
        
          <a href='jogos_recentes.php' class='btn btn-dark' style='margin: 0 auto; color:white; margin-right:15px; text-shadow: 1px 1px black; max-width:60%; max-height:100%; font-weight:bold;'>Jogos Recentes</a>
            <a href='melhores_review.php' class='btn btn-dark' style='margin: 0 auto; color:white; margin-left:15px; text-shadow: 1px 1px black; max-width:60%; max-height:100%; font-weight:bold;'>Melhores Avaliados</a>

</div>
      </div>
    </div>";
}

if ($total_pages > 1) {
    $link_style = 'style="font-size:30px; background-color:white; border: 2px solid black; color:black; border-radius:20px; margin-left:5px; padding-right:3px; padding-left:3px;"';

    echo '<div style="text-align: center; margin-bottom:10px;">';
    for ($page_number = 1; $page_number <= $total_pages; $page_number++) {
        $url = "reviews_usuario.php?page=" . $page_number;
        echo "<a $link_style href='$url'>$page_number</a>";
        if ($page_number < $total_pages) {
            echo " ";
        }
    }
    echo '</div>';
}
?>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Você tem certeza que quer excluir este comentário?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
                    </div>
                </div>
            </div>
        </div>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwmRWzUGE9VNw6aJfwdgnvwTbkKcwQzT5nlwGkE2riVVkJRLaXvBVbvTqQ8PwHd" crossorigin="anonymous" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous" assync></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous" defer></script>
    </body>
</html>
