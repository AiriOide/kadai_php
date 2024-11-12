<?php
session_start();
require_once 'secure_storage.php';

// ログインしていない場合、ログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メンタルヘルスAIアシスタント</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>メンタルヘルスAIアシスタント</h1>
        <p>ようこそ、<?php echo htmlspecialchars($_SESSION['username']); ?>さん</p>
        <a href="logout.php">ログアウト</a>
        <div id="chat-container">
            <?php
            $messages = get_secure_data('chat_messages');
            if ($messages) {
                foreach ($messages as $message) {
                    $class = $message['role'] === 'user' ? 'user-message' : 'ai-message';
                    echo "<div class='message $class'>{$message['content']}</div>";
                }
            }
            ?>
        </div>
        <div id="emotion-analysis"></div>
        <form id="chat-form">
            <input type="text" id="user-message" name="user_message" placeholder="メッセージを入力してください..." required>
            <button type="submit">送信</button>
            <button type="button" id="voice-input-btn">音声入力</button>
            <button type="button" id="text-to-speech-btn">音声再生</button>
        </form>
        <?php include 'emergency_support.php'; ?>
        <?php include 'progress_tracker.php'; ?>
        <?php include 'analyze_emotion.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="app.js"></script>
</body>
</html>