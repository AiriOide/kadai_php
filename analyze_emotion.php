<?php
//require_once 'vendor/autoload.php';

use Google\Cloud\Language\LanguageClient;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $message = $_POST['message'];
    $emotion = analyze_emotion($message);
    echo json_encode($emotion);
}

function analyze_emotion($message) {
    // Google Cloud Natural Language APIのクレデンシャルファイルへのパスを設定
    putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/your/credentials.json');

    $language = new LanguageClient();
    $annotation = $language->analyzeSentiment($message);
    $sentiment = $annotation->sentiment();

    $score = $sentiment['score'];
    $magnitude = $sentiment['magnitude'];

    if ($score > 0.25) {
        $type = 'positive';
    } elseif ($score < -0.25) {
        $type = 'negative';
    } else {
        $type = 'neutral';
    }

    $intensity = abs($score * $magnitude);

    return [
        'type' => $type,
        'intensity' => min($intensity, 1)
    ];
}