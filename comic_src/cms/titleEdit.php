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
        // デフォルトの変数の読み込み
        $sql = "SELECT * FROM mst_titles WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // データベースから取得した値を変数に格納
        $name_def = $row["name"];
        $author_name_def = $row["author_name"];
        $summary_def = $row["summary"];

    case 'POST':
        if (isset($_POST["name"]) && isset($_POST["author_name"]) && isset($_POST["summary"])) {
            // POSTリクエストが送信されたときにだけ以下の処理を実行する
            if (!empty($_POST["name"])) {
                $id = htmlspecialchars($_POST["title_id"], ENT_QUOTES, "UTF-8");
                $name = htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");
                $author_name = htmlspecialchars($_POST["author_name"], ENT_QUOTES, "UTF-8");
                $summary = htmlspecialchars($_POST["summary"], ENT_QUOTES, "UTF-8");
                echo $author_name;
                // データベースにデータの変更点を反映
                $stmt = $mysqli->prepare("UPDATE mst_titles SET name = ?, author_name = ?, summary = ? WHERE id = ?");
                if (!$stmt) {
                    echo "SQL準備エラー: " . $mysqli->error;
                }
                $stmt->bind_param('sssi', $name, $author_name, $summary, $id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "データが更新されました。";
                } else {
                    echo "データの更新に失敗しました。SQLエラー: " . $stmt->error;
                    exit();
                }
                $stmt->close();
                $mysqli->close();
                header("Location: titleList");
                exit();
            } else {
                // 必須フィールドが空の場合のみエラーメッセージを表示
                echo "マンガ作品名は入力必須です</br>";
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

    <h2 class="text-3xl font-bold underline text-center">マンガ編集</h2>
        <form class="max-w-md mx-auto" action="titleEdit?id=<?php echo $id; ?>" method="post">
            <!-- まんがID をPOST渡すためにマンガIDをHIDDENにしている-->
            <input type="hidden" name="title_id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>" required />
            
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="name"
                value="<?php echo htmlspecialchars($name_def, ENT_QUOTES, 'UTF-8'); ?>"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required />
                <label for="floating_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    マンガ名
                </label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="author_name" 
                value="<?php echo htmlspecialchars($author_name_def, ENT_QUOTES, 'UTF-8'); ?>"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"/><br />
                <label for="floating_author_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    著者名
                </label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <textarea  name="summary" 
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"><?php echo htmlspecialchars($summary_def, ENT_QUOTES, 'UTF-8'); ?></textarea>
                <label for="message" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-0 -z-0 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    要約
                </label>
            </div>
            <input type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
        </form>
    </div></div>
</body>
</html>
