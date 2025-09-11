<?php

function recupVille(string $villeSaisie) {
    $query = urlencode($villeSaisie);
    $url = "https://nominatim.openstreetmap.org/search?q=$query&format=json&addressdetails=1&limit=1&accept-language=fr";

    $options = ["http" => ["method" => "GET", "header" => "User-Agent: Ecoride"]];
    $contexte = stream_context_create($options);
    $response = file_get_contents($url, false, $contexte);
    $data = json_decode($response, true);

    if (!empty($data) && isset($data[0]['address']['city'])) {
        return $data[0]['address']['city'];

    } elseif (!empty($data) && isset($data[0]['display_name'])) {
        return explode(',', $data[0]['display_name'])[0];
    } else {
        return null;
    }
}


/*CACHE*/

function recupVilleCache($villeSaisie) {
    if (isset($_SESSION['cache_villes'][$villeSaisie])) {
        return $_SESSION['cache_villes'][$villeSaisie];
    }

    $ville = recupVille($villeSaisie);
    $_SESSION['cache_villes'][$villeSaisie] = $ville;
    return $ville;
}