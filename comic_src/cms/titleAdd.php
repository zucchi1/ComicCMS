<?php
session_start();
$mysqli = new mysqli('localhost', 'brightech', 'brightech', 'test');

// 接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
} else {
    $mysqli->set_charset('utf8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST["name"]) && isset($_POST["author_name"]) && isset($_POST["summary"])) {
    // POSTリクエストが送信されたときにだけ以下の処理を実行する
    if (!empty($_POST["name"])) {
        $name = htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");
        $author_name = htmlspecialchars($_POST["author_name"], ENT_QUOTES, "UTF-8");
        $summary = htmlspecialchars($_POST["summary"], ENT_QUOTES, "UTF-8");

        // データベースにデータを挿入
        $stmt = $mysqli->prepare("INSERT INTO mst_titles (name, author_name, summary) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $author_name, $summary);
        $stmt->execute();
        $stmt->close();
        echo "データが追加されました。";
        header("Location: titleList");
        exit();
    } else {
        // 必須フィールドが空の場合のみエラーメッセージを表示
        echo "マンガ作品名などは入力必須です</br>";
    }

    // 切断
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-50">

    <div class="container mx-auto mt-10">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-center text-gray-800">マンガ追加</h2>
            <form action="titleAdd" method="post" class="mt-6">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">マンガ名</label>
                    <input type="text" name="name" id="name"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring focus:border-red-500"
                        required />
                </div>

                <div class="mb-4">
                    <label for="author_name" class="block text-gray-700 text-sm font-bold mb-2">著者名</label>
                    <input type="text" name="author_name" id="author_name"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring focus:border-red-500" />
                </div>

                <div class="mb-4">
                    <label for="summary" class="block text-gray-700 text-sm font-bold mb-2">要約</label>
                    <textarea name="summary" id="summary" rows="4"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring focus:border-red-500"></textarea>
                </div>

                <div class="text-center">
                    <input type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300"
                        value="追加">
                </div>
            </form>
        </div>
    </div>

</body>

</html>
