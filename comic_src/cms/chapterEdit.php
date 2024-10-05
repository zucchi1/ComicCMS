<?php
session_start();
// 接続
$mysqli = new mysqli('localhost', 'brightech', 'brightech', 'test');

// 接続状況の確認
if($mysqli->connect_error){
    echo $mysqli->connect_error;
    exit();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $id = $_GET['id'];
        if(!$id) {
            echo "タイトルIDが指定されていません";
            exit;
        }
        if($_GET['status']){
            echo "現在以降の時刻を入力して下さい.<br />現在の日本時刻: " .date('Y-m-d H:i:s');
        }
        // デフォルトの変数の読み込み
        $sql = "SELECT * FROM mst_chapters WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        // データベースから取得した値を変数に格納
        $title_id_def = $row["title_id"];
        $name_def = $row["name"];
        $start_date_def = $row["start_date"];
        $mysqli->close();
        break;

    case 'POST':
        if (isset($_POST["chapter_id"]) && isset($_POST["name"]) && isset($_POST["start_date"])) {
            // POSTリクエストが送信されたときにだけ以下の処理を実行する
            $chapter_id = htmlspecialchars($_POST['chapter_id'], ENT_QUOTES, "UTF-8");
            if(!empty($_POST["start_date"]) && $_POST["start_date"]<date('Y-m-d H:i:s')){
                //公開日時に未来の時刻が入力されてされている場合メッセージ表示
                header("Location: chapterEdit?id={$chapter_id}&status=error");
            } else {
                if (!empty($_POST["chapter_id"]) && !empty($_POST["name"])) {
                    $name = htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");
                    $start_date = htmlspecialchars($_POST["start_date"], ENT_QUOTES, "UTF-8");
                    // データベースにデータの変更点を反映
                    if($start_date==""){
                        $start_date=NULL;
                    }
                    $stmt = $mysqli->prepare("UPDATE mst_chapters SET name = ?, start_date= ? WHERE id = ? ");
                    if (!$stmt) {
                        echo "SQL準備エラー: " . $mysqli->error;
                    }
                    $stmt->bind_param('ssi',$name, $start_date, $chapter_id);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        echo "データが更新されました。";
                    } else {
                        echo "データの更新に失敗しました。SQLエラー: " . $stmt->error;
                    }
                    //$stmt->close();
                    $mysqli->close();
                    header("Location: chapterList?id={$_POST["title_id"]}");
                    exit();
                } else {
                    // 必須フィールドが空の場合のみエラーメッセージを表示
                    echo "マンガ作品名などは入力必須です</br>";
                    exit;
                }
            }
        }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マンガ編集</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-lg">

        <h2 class="text-3xl font-bold underline text-center">[マンガID:<?= $title_id_def ?> / チャプタID:<?= $id ?> / チャプタ名<?= $name_def ?>]の編集</h2>
        <br/>
        <form class="max-w-md mx-auto" action="chapterEdit?id=<?php echo $id; ?>" method="post">


                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="name"
                    value="<?php echo htmlspecialchars($name_def, ENT_QUOTES, 'UTF-8'); ?>"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"  required />
                    <label for="floating_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        チャプタ名
                    </label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input type="datetime-local"name="start_date"
                    value="<?= $start_date_def ?>"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    <label for="floating_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        公開開始日
                    </label>
                </div>
                <button type="submit" name="id" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800" value="<?=$_GET['id'] ?>" readonly >新規追加</button>

                <!-- チャプタIDをPOST渡すためHIDDENにしている-->
                <input type="hidden" name="chapter_id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>" readonly />
                <!-- まんがID をPOST渡すためにマンガIDをHIDDENにしている(chapterListへのリダイレクトに必要)-->
                <input type="hidden" name="title_id" value="<?php echo htmlspecialchars($title_id_def, ENT_QUOTES, 'UTF-8'); ?>" required />
                <!--
                チャプタの名前<br/> <input type="text" name="name" value="<?php echo htmlspecialchars($name_def, ENT_QUOTES, 'UTF-8'); ?>" required /><br />
                公開開始日<br/><input type="datetime-local"name="start_date"  value="<?= $start_date_def ?>"/><br />
                <input type="submit" value="保存" />
                -->
            </form>
        </body>
</div></div>
</html>
