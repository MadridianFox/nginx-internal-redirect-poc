<?php

$file = match ($_SERVER['REQUEST_URI']) {
    '/virtual/path/product-1.jpg' => '/realpath/5628845.jpg',
    '/virtual/path/product-2.jpg' => '/realpath/26580513.jpg',
    '/virtual/path/product-3.jpg' => 'https://hsto.org/getpro/habr/hub/de0/f55/204/de0f552047d74d5841a0fd05ec19a98e.png',
    default => null,
};

/*
 * Если у нас запросили известный нам файл, то возвращаем особый редирект.
 * Он делается не кодом 3**, а заголовком X-Accel-Redirect.
 * Nginx, видя такой заголовок, сразу делает внутренний редирект.
 *
 * Если мы хотим отдать локальный файл, то просто редиректим на него.
 * А если хотим проксировать на другой сервер (например на imgproxy), то редиректим на локейшен /proxy,
 * который проксирует на тот адрес, который мы укажем в заголовке redirect_uri
 */
if ($file) {
    if (str_starts_with($file, 'https')) {
        header("X-Accel-Redirect: /proxy");
        header("redirect_uri: {$file}");
    } else {
        header("X-Accel-Redirect: {$file}");
    }
    header("Content-Type: ");
} else {
    echo "<a href='/virtual/path/product-1.jpg'>/virtual/path/product-1.jpg</a>";
    echo "<br>";
    echo "<a href='/virtual/path/product-2.jpg'>/virtual/path/product-2.jpg</a>";
    echo "<br>";
    echo "<a href='/virtual/path/product-3.jpg'>/virtual/path/product-3.jpg</a>";
}