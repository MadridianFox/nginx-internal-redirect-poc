<?php

$redirectsList = [
    '/catalog/alcohol' => '/redirected/alcohol',
    '/catalog/meat' => '/redirected/fresh-meat',
];

switch (true) {
    case $_SERVER['REQUEST_URI'] == '/':
        echo "Hello<br>";
        echo "<a href='/catalog/alcohol'>redirect 1</a><br>";
        echo "<a href='/catalog/meat'>redirect 2</a><br>";
        echo "<a href='/banana'>real not found</a><br>";
        break;
    case str_starts_with($_SERVER['REQUEST_URI'], '/redirected'):
        echo "Redirected to {$_SERVER['REQUEST_URI']}";
        break;
    case $_SERVER['REQUEST_URI'] == '/search-redirect':
        $needle = current(explode('?', $_SERVER['OLD_URI']));
        if (isset($redirectsList[$needle])) {
            header("Location: {$redirectsList[$needle]}");
        } else {
            http_response_code(404);
            echo "Real not found";
        }
        break;
    default:
        http_response_code(404);
        echo "Maybe not found";
}

echo "<br><br><a href='/'>home</a>";