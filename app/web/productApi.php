<?php

// Get the target language and text from the request
$targetLanguage = $_GET['lang'] ?? 'zh'; // Default to Chinese if not provided
$text = $_POST['text'] ?? '';

if (empty($text)) {
    echo json_encode(['error' => 'No text provided']);
    exit;
}

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://google-translate1.p.rapidapi.com/language/translate/v2",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => http_build_query([
        'q' => $text,
        'target' => $targetLanguage
    ]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/x-www-form-urlencoded",
        "x-rapidapi-host: google-translate1.p.rapidapi.com",
        "x-rapidapi-key: bcd4c80433msh8f8b711953bca81p14f6a0jsn85fa5bd68d33"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo json_encode(['error' => 'cURL Error: ' . $err]);
} else {
    $responseData = json_decode($response, true);
    if (isset($responseData['data']['translations'][0]['translatedText'])) {
        echo json_encode(['translatedText' => $responseData['data']['translations'][0]['translatedText']]);
    } else {
        echo json_encode(['error' => 'Translation failed']);
    }
}
