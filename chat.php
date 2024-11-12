<?php
session_start();
require_once 'secure_storage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_message'])) {
    $user_message = htmlspecialchars($_POST['user_message']);
    $ai_response = get_ai_response($user_message);

    $messages = get_secure_data('chat_messages') ?: [];
    $messages[] = ['role' => 'user', 'content' => $user_message];
    $messages[] = ['role' => 'ai', 'content' => $ai_response];
    set_secure_data('chat_messages', $messages);

    echo $ai_response;
}

function get_ai_response($message) {
    // ここでAI応答を生成するロジックを実装します
    // 実際のプロジェクトでは、OpenAI APIなどを使用することになります
    $responses = [
        "ご心配なことがあるようですね。もう少し詳しく教えていただけますか？",
        "その気持ち、よくわかります。一緒に解決策を考えていきましょう。",
        "ストレスを感じているようですね。深呼吸をして、リラックスすることから始めてみましょう。",
        "前向きに考えようとしているのは素晴らしいことです。その姿勢を大切にしてください。",
        "困難な状況に直面しているようですが、あなたには乗り越える力があります。一緩に頑張りましょう。"
    ];
    return $responses[array_rand($responses)];
}