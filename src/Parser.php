<?php

require __DIR__ . '/../vendor/autoload.php';

use Alex\CsvParser\Models\User;

function getFile()
{
    $user = new User();
    $user->setId(1);
    $user->setEmail('example@gmail.com');
    $user->setPhone('+222222222');
    $user->setCard('11111111111');

    var_dump($user);
}

getFile();
