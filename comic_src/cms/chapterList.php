<?php
session_start();
$id = $_GET['id'];

echo "<h2 class='text-4xl font-bold mb-6 text-gray-900 dark:text-white text-center'>チャプター一覧 (Title ID: {$id})</h2>";
echo '<div class="text-center mb-6">
        <form action="chapterAdd" method="get">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-700 dark:hover:bg-red-800 dark:focus:ring-red-800" name="id" value="'.$id.'">新規追加</button>
        </form>
        <form action="titleList" method="post">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-gray-600 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:bg-gray-700 dark:hover:bg-gray-800 dark:focus:ring-gray-800">マンガ一覧に戻る</button>
        </form>
        </div>';


// 接続
$mysqli = new mysqli('localhost', 'brightech', 'brightech', 'test');

// 接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
}

$sql = "SELECT * FROM `mst_chapters` WHERE title_id=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

echo '<table class="min-w-full table-auto text-sm text-left text-gray-500 dark:text-gray-400">';
echo '
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th class="px-6 py-3">操作</th>
            <th class="px-6 py-3">ID</th>
            <th class="px-6 py-3">チャプタ名</th>
            <th class="px-6 py-3">公開日時</th>
            <th class="px-6 py-3">作成日</th>
            <th class="px-6 py-3">更新日</th>
        </tr>
    </thead>';

while ($row = $result->fetch_assoc()) {
    echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
    echo '<td class="px-6 py-4">
            <form action="chapterEdit" method="get">
                <button type="submit" class="px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700" name="id" value="'.$row['id'].'">編集</button>
            </form>
          </td>';
    echo "<td class='px-6 py-4'>{$row['id']}</td>";
    echo "<td class='px-6 py-4'>{$row['name']}</td>";
    echo "<td class='px-6 py-4'>{$row['start_date']}</td>";
    echo "<td class='px-6 py-4'>{$row['created_at']}</td>";
    echo "<td class='px-6 py-4'>{$row['updated_at']}</td>";
    echo "</tr>";
}
echo '</table>';

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<br/>
</html>