<?php
require_once 'secure_storage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mood'])) {
    $mood_entries = get_secure_data('mood_entries') ?: [];
    $mood_entries[] = [
        'date' => date('Y-m-d H:i:s'),
        'mood' => intval($_POST['mood'])
    ];
    set_secure_data('mood_entries', $mood_entries);
}

$mood_entries = get_secure_data('mood_entries') ?: [];
$average_mood = count($mood_entries) > 0 ? array_sum(array_column($mood_entries, 'mood')) / count($mood_entries) : 0;
?>

<div id="progress-tracker">
    <h2>進捗トラッカー</h2>
    <form action="" method="post">
        <label for="mood">今日の気分 (1-10):</label>
        <input type="range" id="mood" name="mood" min="1" max="10" value="5">
        <output for="mood"></output>
        <button type="submit">記録</button>
    </form>
    <div>
        <p>記録回数: <?php echo count($mood_entries); ?></p>
        <p>平均気分: <?php echo number_format($average_mood, 1); ?></p>
        <progress value="<?php echo $average_mood; ?>" max="10"></progress>
    </div>
</div>

<script>
document.getElementById('mood').addEventListener('input', function(e) {
    e.target.nextElementSibling.value = e.target.value;
});
</script>