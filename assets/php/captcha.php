<?php

define('USE_SESSION', true);

// 1. Генерируем код капчи
// 1.1. Устанавливаем символы, из которых будет составляться код капчи
$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
// 1.2. Количество символов в капче
$length = 6;
// 1.3. Генерируем код
$code = substr(str_shuffle($chars), 0, $length);

if (USE_SESSION) {
  // 2a. Используем сессию
  session_start();
  $_SESSION['captcha'] =  crypt($code, '$1$itchief$7');
  session_destroy();
} else {
  // 2a. Используем куки (время действия 600 секунд)
  $value = crypt($code, '$1$itchief$7');
  $expires = time() + 600;
  setcookie('captcha', $value, $expires, '/', 'test.ru', false, true);
}

// 3. Генерируем изображение
// 3.1. Создаем новое изображение из файла
$image = imagecreatefrompng(__DIR__ . '/files/bg.png');
// 3.2 Устанавливаем размер шрифта в пунктах
$size = 36;
// 3.3. Создаём цвет, который будет использоваться в изображении
$color = imagecolorallocate($image, 66, 182, 66);
// 3.4. Устанавливаем путь к шрифту
$font = __DIR__ . '/files//oswald.ttf';
// 3.5 Задаём угол в градусах
$angle = rand(-10, 10);
// 3.6. Устанавливаем координаты точки для первого символа текста
$x = 56;
$y = 64;
// 3.7. Наносим текст на изображение
imagefttext($image, $size, $angle, $x, $y, $color, $font, $code);
// 3.8 Устанавливаем заголовки
header('Cache-Control: no-store, must-revalidate');
header('Expires: 0');
header('Content-Type: image/png');
// 3.9. Выводим изображение
imagepng($image);
// 3.10. Удаляем изображение
imagedestroy($image);
