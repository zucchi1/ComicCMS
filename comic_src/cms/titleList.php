<?php
session_start();

if (isset($_SESSION['user_id'])) {
    echo "<h2 class='text-4xl font-bold mb-6 text-gray-900 dark:text-white text-center'>マンガ一覧</h2>";
    echo '<div class="text-center mb-6">
            <form action="titleAdd" method="post">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-700 dark:hover:bg-red-800 dark:focus:ring-red-800">新規追加</button>
            </form>
          </div>';
}

// 接続
$mysqli = new mysqli('localhost', 'brightech', 'brightech', 'test');

// 接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
}

$sql = "SELECT * FROM `mst_titles`";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

echo '<table class="min-w-full table-auto text-sm text-left text-gray-500 dark:text-gray-400">';
echo '
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th class="px-6 py-3">操作</th>
            <th class="px-6 py-3">ID</th>
            <th class="px-6 py-3">作品名</th>
            <th class="px-6 py-3">著者名</th>
            <th class="px-6 py-3">説明</th>
            <th class="px-6 py-3">作成日</th>
            <th class="px-6 py-3">更新日</th>
        </tr>
    </thead>';

while ($row = $result->fetch_assoc()) {
    echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
    echo '<td class="px-6 py-4 flex space-x-2">
            <form action="titleEdit" method="get">
                <button type="submit" class="px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700" name="id" value="' . $row['id'] . '">編集</button>
            </form>
            <form action="chapterList" method="get">
                <button type="submit" class="px-3 py-2 text-xs font-medium text-white bg-gray-600 rounded-lg hover:bg-gray-700" name="id" value="' . $row['id'] . '">チャプター</button>
            </form>
          </td>';
    echo "<td class='px-6 py-4'>{$row['id']}</td>";
    echo "<td class='px-6 py-4'>{$row['name']}</td>";
    echo "<td class='px-6 py-4'>{$row['author_name']}</td>";
    echo "<td class='px-6 py-4'>{$row['summary']}</td>";
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
    <form action="logout" method="post">
	<button type="submit" class="px-3 py-2 text-xs font-medium text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800" name="submit" value="clicked">ログアウト</button>
    </form>
    <form action="newUser" method="get">
	<button type="submit" class="px-3 py-2 text-xs font-medium text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800" name="submit" value="clicked">管理者追加</button>
    </form>
    <br/>
</html>