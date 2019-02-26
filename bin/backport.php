<?php
require __DIR__.'/../vendor/autoload.php';


$client = new \BackPort\Client();

$client
    ->setProjectDir(__DIR__.'/..')
    ->setDirsToPort([
        __DIR__.'/../src'
    ])
;

echo "DONE\n";
