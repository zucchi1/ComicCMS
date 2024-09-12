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
    header("Location: newUser");
    // echo "userID: {$_SESSION['user_id']} </br>";
    // echo "既にログインしています</br>";
    // exit();
}

// if ($_POST['username'] && $_POST['password']) {
    /**
     * 課題２：データベースにPOSTで取得したusername,password(ハッシュ化)と一致するものがあればセッションを開始し
     * $_SESSION['user_id']にユーザIDを,$_SESSION['user_name']にユーザ名を格納する処理を書いてください
     */
    $name = htmlspecialchars($_POST["username"], ENT_QUOTES, "UTF-8");
    $password = htmlspecialchars($_POST["password"], ENT_QUOTES, "UTF-8");
    $password_hash = hash("sha256", $password);

    /*DBにusernameと同じユーザ名の行を取得しようとする*/
    $sql = "SELECT * FROM trx_users WHERE user_name = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    // echo var_dump($result);

    // ユーザーが存在し、パスワードが一致するか確認
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // データベースのハッシュ化されたパスワードと一致するか確認
        if ($row['password'] === $password_hash) {
            // セッションを開始し、ユーザIDとユーザ名をセッションに格納
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['username'];
            echo "sessionID: {$_SESSION['user_id']}</br>";
            echo "ログイン成功！</br>";
            exit();
        } else {
            echo "パスワードが正しくありません。<br>";
            echo "??sessionID: {$_SESSION['user_id']}</br>";
            echo "検索された行の数{$result->num_rows}";
        }
    } else {
        echo "ユーザー名が見つかりません。";
    }
// }

// 接続を閉じる
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    <h2>ログイン</h2>
    <form action="login" method="post">
        ユーザ: <input type="text" name="username" required /><br />
        パスワード: <input type="password" name="password" required /><br />
        <input type="submit" />
    </form>
</body>

</html>