<?php
set_time_limit(0);
require 'vendor/autoload.php'; // Carrega o autoloader do Composer

use Stichoza\GoogleTranslate\GoogleTranslate;

$key = 'Bearer qrmdc86d684n39ccrmjny54ihzbmt8'; // seu token de acesso IGDB
$client_id = 'w1yfl91psnoxxgbns5ig6909yb25yx'; // seu ID de cliente IGDB

require('conecta.php');

header('Content-Type: text/html; charset=utf-8');
$tr = new GoogleTranslate('pt-br'); // Traduzir para português do Brasil

// Mapeamento dos gêneros
$genres_map = [
    "MOBA" => "MOBA",
    "Point-and-click" => "Point-and-click",
    "Fighting" => "Luta",
    "Shooter" => "Shooter",
    "Music" => "Música",
    "Platform" => "Plataforma",
    "Puzzle" => "Quebra-Cabeça",
    "Racing" => "Corrida",
    "Real Time Strategy (RTS)" => "Estratégia em tempo Real",
    "Role-playing (RPG)" => "RPG",
    "Simulator" => "Simulação",
    "Sport" => "Esporte",
    "Strategy" => "Estratégia",
    "Turn-based strategy (TBS)" => "Estratégia em turnos",
    "Tactical" => "Tático",
    "Hack and slash/Beat 'em up" => "Hack and slash/Beat 'em up",
    "Quiz/Trivia" => "Quiz/Trivia",
    "Pinball" => "Pinball",
    "Adventure" => "Aventura",
    "Indie" => "Indie",
    "Arcade" => "Arcade",
    "Visual Novel" => "Visual Novel",
    "Card & Board Game" => "Jogo de carta/tabuleiro"
];

function formatDate($timestamp) {
    return date('Y-m-d', $timestamp);
}

$max_attempts = 5;
$attempt = 0;
$delay = 300000; // 300ms
$limit = 60; // Quantidade de jogos por requisição
$offset = 0;

while (true) {
    $json_url = 'https://api.igdb.com/v4/games';
    $query = "fields name, first_release_date, summary, genres.name, involved_companies.company.name, involved_companies.developer, involved_companies.publisher, cover.url, artworks.url, total_rating_count; where platforms = (6) & cover != null & summary != null; limit $limit; offset $offset; sort total_rating_count desc;";

    $ch = curl_init($json_url);
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Client-ID: ' . $client_id,
            'Authorization: ' . $key,
            'Accept: application/json'
        ),
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $query
    );
    curl_setopt_array($ch, $options);
    $json = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error_message = 'Error: ' . curl_error($ch);
        error_log($error_message);
        $data = [];
    } elseif ($http_code === 429) {
        // Se atingiu o limite, aumenta o tempo de espera e tenta novamente
        $attempt++;
        if ($attempt >= $max_attempts) {
            error_log('Max attempts reached. Exiting...');
            break;
        }
        $delay *= 2;
        error_log('Rate limit hit. Waiting for ' . $delay / 1000000 . ' seconds...');
        usleep($delay);
        continue;
    } elseif ($http_code !== 200) {
        $error_message = "Request failed with HTTP code $http_code. Response: $json";
        error_log($error_message);
        $data = [];
    } else {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error_message = 'JSON decode error: ' . json_last_error_msg();
            error_log($error_message);
            $data = [];
        }
    }

    curl_close($ch);

    // Adicione um log para verificar o conteúdo dos dados recebidos da API
    error_log("Data received from API: " . print_r($data, true));

    if (!empty($data)) {
        foreach ($data as $game) {
            if (isset($game['cover']['url']) && isset($game['summary']) && $game['summary']) {
                $name = $game['name'];
                $release_date = isset($game['first_release_date']) ? formatDate($game['first_release_date']) : "N/A";
                $description = $tr->translate($game['summary']);
                $developer = "N/A";
                $publisher = "N/A";

                // Traduzindo os gêneros com base no mapeamento
                $genres = [];
                if (isset($game['genres'])) {
                    foreach ($game['genres'] as $genre) {
                        $translated_genre = $genres_map[$genre['name']] ?? $genre['name'];
                        $genres[] = $translated_genre;
                    }
                }
                $genres = implode(', ', $genres);

                if (isset($game['involved_companies'])) {
                    foreach ($game['involved_companies'] as $company) {
                        if (isset($company['developer']) && $company['developer']) {
                            $developer = $company['company']['name'];
                        }
                        if (isset($company['publisher']) && $company['publisher']) {
                            $publisher = $company['company']['name'];
                        }
                    }
                }

                // Melhorar a qualidade da imagem de capa
                $cover_url = str_replace('t_thumb', 't_cover_big_2x', $game['cover']['url']);
                
                // Obter a melhor qualidade da artwork ou usar a cover em 1080p se não existir artwork
                $artwork_url = null;
                if (isset($game['artworks']) && count($game['artworks']) > 0) {
                    $artwork_url_1080p = str_replace('t_thumb', 't_1080p', $game['artworks'][0]['url']);
                    $artwork_url = $artwork_url_1080p ?: str_replace('t_thumb', 't_cover_big_2x', $game['cover']['url']);
                } else {
                    $artwork_url = str_replace('t_thumb', 't_cover_big_2x', $game['cover']['url']);
                }

                date_default_timezone_set('America/Sao_Paulo');
                $tempo = time();
                $horarioatual = date("Y-m-d H:i:s", $tempo);

                // Verifica se o jogo já existe no banco de dados com base em nome e data de lançamento
                $stmt_check = $conecta->prepare("SELECT COUNT(*) FROM games WHERE nome_jogo = ? AND data_lancamento = ?");
                $stmt_check->bind_param("ss", $name, $release_date);
                $stmt_check->execute();
                $stmt_check->bind_result($count);
                $stmt_check->fetch();
                $stmt_check->close();

                if ($count > 0) {
                    // Jogo já existe, pula para o próximo
                    error_log("Jogo já existe: $name");
                    continue;
                }

                // Preparar e executar a inserção no banco de dados
                $stmt = $conecta->prepare("INSERT INTO games (nome_jogo, data_lancamento, desc_jogo, generos, desenvolvedor, publisher, img_jogo, imagem_artwork, horario_postado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt) {
                    error_log("Prepare failed: " . $conecta->error);
                    continue;
                }
                $stmt->bind_param("sssssssss", $name, $release_date, $description, $genres, $developer, $publisher, $cover_url, $artwork_url, $horarioatual);
                if (!$stmt->execute()) {
                    error_log("Execute failed: " . $stmt->error);
                } else {
                    error_log("Inserted: $name");
                }
                $stmt->close();

                // Reseta as tentativas e delay após uma inserção bem-sucedida
                $attempt = 0;
                $delay = 250000; // 250ms
            } else {
                error_log("Jogo sem capa ou resumo: " . print_r($game, true));
            }
        }
        // Atualiza o offset para a próxima página
        $offset += $limit;
    } else {
        error_log("No data received from API.");
        break; // Sai do loop se não houver dados
    }

    // Espera 250 milissegundos entre as requisições (4 requisições por segundo)
    usleep($delay);
}

$conecta->close();
?>
