<?php
session_start();

//MySQLに接続
$mysqli = new mysqli('localhost', 'brightech', 'brightech', 'test');

if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
}

// SESSION[user_id]に値入っていればログインしたとみなす
if (isset($_SESSION['user_id'])) {
    header("Location: titleList");
    exit();
}

// フォームが送信されたか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTリクエストが送信されたときにだけ以下の処理を実行する

    $name = htmlspecialchars($_POST["mail_address"], ENT_QUOTES, "UTF-8");
    $password = htmlspecialchars($_POST["password"], ENT_QUOTES, "UTF-8");
    $password_hash = hash("sha256", $password);

    // DBにusernameと同じユーザ名の行を取得しようとする
    $sql = "SELECT * FROM adm_admin_users WHERE mail_address = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    // ユーザーが存在し、パスワードが一致するか確認
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // データベースのハッシュ化されたパスワードと一致するか確認
        if ($row['password'] === $password_hash) {
            // セッションを開始し、ユーザIDとユーザ名をセッションに格納
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['mail_address'] = $row['mail_address'];
            echo "sessionID: {$_SESSION['user_id']}</br>";
            echo "ログイン成功！</br>";
            // タイトル一覧ページにリダイレクト
            header("Location: titleList");
            exit();
        } else {
            echo "パスワードが正しくありません。<br>";
        }
    } else {
        echo "ユーザー名が見つかりません。";
    }

    // 接続を閉じる
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <h2 class="text-3xl font-bold underline text-center">ログイン</h2>
    <br/>
    <form action="login" class="max-w-sm mx-auto" method="post">
    <div class="mb-5">
    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        メアド: </label>
        <input type="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="mail_address" required /><br />
    </div>
    <div class="mb-5">
        パスワード: 
        <input type="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="password" required /><br />
        <input type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
    </div>
        
    </form>
</body>

</html>
