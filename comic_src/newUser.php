<?php
$mysqli = new mysqli('localhost', 'brightech', 'brightech', 'test');

// 接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
} else {
    $mysqli->set_charset('utf8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTリクエストが送信されたときにだけ以下の処理を実行
    if ($_POST["mail_address"] && $_POST["password"]) {
        $mail_address = htmlspecialchars($_POST["mail_address"], ENT_QUOTES, "UTF-8");
        $password = htmlspecialchars($_POST["password"], ENT_QUOTES, "UTF-8");
        $password_hash = hash("sha256", $password);

        $stmt = $mysqli->prepare("INSERT INTO adm_admin_users (mail_address, password) VALUES (?, ?)");
        $stmt->bind_param('ss', $mail_address, $password_hash);
        $stmt->execute();
        $stmt->close();

        // メッセージをクエリパラメータに追加してリダイレクト
        header("Location: newUser?message=管理者が追加されました");
    } else {
        // エラーメッセージをクエリパラメータに追加してリダイレクト
        header("Location: newUser?error=メールアドレスとパスワードは入力必須です");
    }

    // 切断
    $mysqli->close();
    exit();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['return']) {
    header('Location: titleList');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-200">

    <div class="container mx-auto mt-10">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-center text-gray-800">管理者追加</h2>

            <!-- メッセージ表示 -->
            <?php
            if (isset($_GET['message'])) {
                echo '<div class="mb-4 text-center text-green-500 font-semibold">' . htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8') . '</div>';
            } elseif (isset($_GET['error'])) {
                echo '<div class="mb-4 text-center text-red-500 font-semibold">' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') . '</div>';
            }
            ?>

            <form action="newUser" method="post" class="mt-6">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">メールアドレス</label>
                    <input type="email" name="mail_address" id="name"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring focus:border-blue-500"
                        required />
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">パスワード</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring focus:border-blue-500"
                        required />
                </div>

                <div class="text-center">
                    <input type="submit"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-300"
                        value="追加">
                </div>
            </form>
            <form action="newUser" method="get">
                <button type="submit"
                    class="px-3 py-2 text-xs font-medium text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"
                    name="return" value="return">マンガ一覧に戻る</button>
            </form>
        </div>
    </div>

</body>

</html>