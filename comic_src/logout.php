<?php
  /**
   * 課題3：セッションが確立しているとき，セッションを破棄してログアウトする処理を書いてください
   */
  session_start();

  if (isset($_SESSION['user_id'])) {
      // セッション変数を全てクリア
      $_SESSION = array();

      // セッションを破棄
      if (session_id() != "" || isset($_COOKIE[session_name()])) {
          setcookie(session_name(), '', time() - 3600, '/');
      }

      // セッションを終了
      session_destroy();

      // ログインページにリダイレクト
      header("Location: login");
      exit();
  } else {
      echo "セッションが存在しません。ログインしていません。";
  }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>ログアウト</h2>
		<form action="login" method="post">
		  <button type="submit" name="logout" value="send">ログアウト</button>
		</form>
	</body>
</html>