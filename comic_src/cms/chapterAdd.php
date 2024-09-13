<?php
session_start();
$mysqli = new mysqli('localhost', 'brightech', 'brightech', 'test');
$id = $_GET['id'];

// 接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
} else {
    $mysqli->set_charset('utf8');
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
        break;
    case 'POST':
        if (isset($_POST["name"])) {
            // POSTリクエストが送信されたときにだけ以下の処理を実行する
            $id = htmlspecialchars($_POST['id'], ENT_QUOTES, "UTF-8");
            if(!empty($_POST["start_date"]) && $_POST["start_date"]<date('Y-m-d H:i:s')){
                //公開日時に未来の時刻が入力されてされている場合メッセージ表示
                // header("Location: chapterAdd?id={$id}");
                header("Location: chapterAdd?id={$id}&status=error");
            } else {
                if (!empty($_POST["name"])) {
                    $name = htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");
                    $start_date = $_POST["start_date"];
                    // データベースにデータを挿入
                    if($start_date==""){
                        $start_date=NULL;
                    }
                    $stmt = $mysqli->prepare("INSERT INTO mst_chapters (title_id, name, start_date) VALUES (?, ?, ?)");
                    $stmt->bind_param('sss', $id, $name, $start_date);
                    $stmt->execute();
                    $last_insert_id=$stmt->insert_id;
                    
                    $stmt->close();
                    echo "データが追加されました。";
                    header("Location: chapterList?id={$id}");
                    exit();
                }else{
                    // 必須フィールドが空の場合のみエラーメッセージを表示
                    $alert = "<script type='text/javascript'>alert('チャプタ名は入力して下さい。');</script>";
                    echo $alert;
                }
            }
        
            // 切断
            $mysqli->close();
        }
        break;
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
            <h2 class="text-3xl font-semibold text-center text-gray-800">[マンガID:<?= $id ?>]のチャプタ追加</h2>
                    <!-- メッセージ表示 -->
        <?php
        if (isset($_GET['message'])) {
            echo '<div class="mb-4 text-center text-green-500 font-semibold">' . htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8') . '</div>';
        } elseif (isset($_GET['error'])) {
            echo '<div class="mb-4 text-center text-red-500 font-semibold">' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') . '</div>';
        }
        ?>
            <form action="chapterAdd" method="post" class="mt-6">
                <!-- マンガIDを隠してPOSTに渡す -->
                <input type="hidden" name="id" value="<?=$_GET['id'] ?>" readonly />

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">チャプタ名</label>
                    <input type="text" name="name" id="name"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring focus:border-red-500"
                        required />
                </div>

                <div class="mb-4">
                    <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">公開予定日</label>
                    <input type="datetime-local" name="start_date" id="start_date"
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring focus:border-red-500" />
                </div>

                <div class="text-center">
                    <button type="submit" name="id" value="<?=$id ?>"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">
                        新規追加
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>

