<?php
require __DIR__.'/../vendor/autoload.php';


$client = new \BackPort\Client();
$client->execute(
    __DIR__.'/..',
    [
    __DIR__.'/../src'
]);

echo "DONE\n";
