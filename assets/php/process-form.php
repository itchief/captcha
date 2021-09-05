<?php

// 1a
session_start();
$captcha = $_SESSION['captcha'];
unset($_SESSION['captcha']);
session_destroy();
// 1b
//$captcha = $_COOKIE['captcha'];
//unset($_COOKIE['captcha']);
//setcookie('captcha', '', time() - 3600, '/', 'test.ru', false, true);

$result = ['success' => false];
$code = $_POST['captcha'];

if (empty($code)) {
  $result['errors'][] = ['captcha', 'Пожалуйста введите код!'];
} else {
  $code = crypt(trim($code), '$1$itchief$7');
  $result['success'] = $captcha === $code;
  if (!$result['success']) {
    $result['errors'][] = ['captcha', 'Введенный код не соответствует изображению!'];
  }
}

echo json_encode($result);
