<?php

$eng = json_decode(file_get_contents(__DIR__ . "/../lang/en.json"), true);
$leng = array_map('strtolower', $eng);
$eng = array_merge(array_flip($eng), array_flip($leng));
$html = array();
$new = array();

foreach (glob(__DIR__ . "/../html/*.html") as $file) {
    $html = file_get_contents($file);
    $html = preg_replace_callback("/{{([^}]+)}}/smU", function($match) use ($eng, &$new, $file, &$html) {
        $text = preg_replace("/[ \n\r\t]+/",  " ", $match[1]);
        $text = trim($text);
        $ltext = strtolower($text);
        if ($text != strip_tags($text)) {
            var_dump($text);exit;
            return $match[0];
        }
        if (!empty($eng[$text])) {
            return '[$' . $eng[$text]  . ']';
        }
        if (!empty($eng[$ltext])) {
            return '[$' . $eng[$ltext]  . ']';
        }

        $new[] = $text;

        return $match[0];
    }, $html);
    //file_put_contents(__DIR__  . '/' . basename($file), $html);
    file_put_contents($file, $html);
}

file_put_contents(__DIR__ . "/html.json", json_encode($html));
file_put_contents(__DIR__ . '/new.json', json_encode(array_values(array_unique($new)), JSON_PRETTY_PRINT));
