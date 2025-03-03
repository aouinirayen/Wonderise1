<?php
// Script de test pour la clé API OpenAI

// Récupérer la clé API depuis le fichier .env
$envFile = file_get_contents(__DIR__ . '/.env');
$matches = [];
preg_match('/OPENAI_API_KEY=([^\n]+)/', $envFile, $matches);
$apiKey = isset($matches[1]) ? trim($matches[1]) : '';

echo "Test de la clé API OpenAI\n";
echo "------------------------\n";
echo "Clé API: " . (empty($apiKey) ? "Non trouvée" : substr($apiKey, 0, 10) . "..." . substr($apiKey, -5)) . "\n\n";

if (empty($apiKey)) {
    die("Erreur: Clé API non trouvée dans le fichier .env\n");
}

// Créer la requête à l'API OpenAI
$ch = curl_init('https://api.openai.com/v1/chat/completions');

$data = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => 'Tu es un assistant utile.'],
        ['role' => 'user', 'content' => 'Dis bonjour en français.']
    ],
    'max_tokens' => 50
];

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
];

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_VERBOSE, true);

echo "Envoi de la requête à OpenAI...\n";
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Code de réponse HTTP: $httpCode\n\n";

if (curl_errno($ch)) {
    echo "Erreur cURL: " . curl_error($ch) . "\n";
} elseif ($httpCode !== 200) {
    echo "Erreur API: $httpCode\n";
    echo "Réponse: " . $response . "\n";
} else {
    echo "Succès! L'API a répondu correctement.\n";
    $responseData = json_decode($response, true);
    echo "Réponse: " . ($responseData['choices'][0]['message']['content'] ?? "Non trouvée") . "\n";
}

curl_close($ch);
