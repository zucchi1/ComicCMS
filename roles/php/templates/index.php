<?php
session_start();
// エラーレポートを有効にする（開発時のみ推奨）
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

// リクエストURIを取得
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// ルートからスラッシュを取り除く
$route = trim($request, '/');

// ルーティングの定義
$routes = [
    '' => [
        "route" => 'HomeController.php', // ホームページ
        "login_require" => true,
    ],
    'login' => [
        "route" => 'LoginController.php', // ログイン
        "login_require" => false,
    ],
    'logout' => [
        "route" => 'LogoutController.php', // ログアウト
        "login_require" => true,
    ],
    'newUser' => [
        "route" => 'newUserController.php', // 新規ユーザー
        "login_require" => true,
    ]
    // 追加のルートをここに定義
];


// ルートに対応するコントローラを決定
if (array_key_exists($route, $routes)) {
    $controller = $routes[$route]["route"];

    // 未ログイン状態でログイン必須ページにアクセスした際は、エラーページに飛ばす
    if (!isset($_SESSION['user_id']) && $routes[$route]["login_require"]) {
        $controller = "AuthorizedErrorController.php";
    }    

} else {
    // ルートが定義されていない場合は404エラーページ
    $controller = 'NotFoundController.php';
}

// コントローラファイルのパスを作成
$controllerPath = __DIR__ . '/controllers/' . $controller;

// コントローラファイルが存在するか確認
if (file_exists($controllerPath)) {
    include_once $controllerPath;
} else {
    // コントローラファイルが存在しない場合は404エラーページ
    header("HTTP/1.0 404 Not Found");
    echo "404 Not ";
    exit();
}
?>
