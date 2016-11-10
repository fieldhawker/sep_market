<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>
<body>
<?php
$status_code = $exception->getStatusCode();
$message = $exception->getMessage();

if (! $message) {
    switch ($exception->getStatusCode()) {
        case 400:
            $message = 'Bad Request';
            break;
        case 401:
            $message = '認証に失敗しました';
            break;
        case 403:
            $message = 'アクセス権がありません';
            break;
        case 404:
            $message = '存在しないページです';
            break;
        case 408:
            $message = 'タイムアウトです';
            break;
        case 414:
            $message = 'リクエストURIが長すぎます';
            break;
        case 500:
            $message = 'Internal Server Error';
            break;
        case 503:
            $message = 'Service Unavailable';
            break;
        default:
            $message = 'エラー';
            break;
    }
}
?>
<h1>{{ $status_code }} {{ $message }}</h1>
</body>
</html>