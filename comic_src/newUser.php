<?php
/**
 * 課題１：mysqliを用いてMySQLに接続し，POSTで受け取ったデータをtrx_usersにINSERTする処理を書いてください
 * パスワードはハッシュ化する必要があるので，以下の$password_hashを用いてください
 */
$mysqli = new mysqli('localhost', 'brightech', 'brightech', 'test');
//接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
} else {
    $mysqli->set_charset('utf8');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTリクエストが送信されたときにだけ以下の処理を実行する
    if($_POST["mail_address"]&&$_POST["password"]){
        $mail_address = htmlspecialchars($_POST["mail_address"], ENT_QUOTES, "UTF-8");
        $password = htmlspecialchars($_POST["password"], ENT_QUOTES, "UTF-8");
        $password_hash = hash("sha256", $password);
        $stmt = $mysqli->prepare("INSERT INTO adm_admin_users (mail_address,password) VALUES (?,?)");

        $stmt->bind_param('ss', $mail_address, $password_hash);
        $stmt->execute();

        $stmt->close();
    } else {
        echo "メールアドレスとパスワードは入力必須です</br>";
    }
    // 切断
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
<h2 class="text-3xl font-bold underline text-center">ユーザ追加</h2>
<br/>
    <form action="newUser" method="post">
        メールアドレス: <input type="email" name="mail_address" required /><br />
        パスワード: <input type="password" name="password" required /><br />
        <input type="submit" />
    </form>
</body>

</html>