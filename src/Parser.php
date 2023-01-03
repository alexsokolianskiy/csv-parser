<?php

require __DIR__ . '/../vendor/autoload.php';

use Alex\CsvParser\Enums\UserColumn;
use Alex\CsvParser\Services\Parsers\UserParser;
use Alex\CsvParser\Services\Readers\CsvReader;
use Alex\CsvParser\Services\Sorts\DoublesList;

function getFile()
{
    $csvReader = new CsvReader(__DIR__ . '/../example-big.csv');
    $userParser = new UserParser($csvReader);
    $users = $userParser->getUsers();
    $dList = new DoublesList($users);
    $dList->findDoubles([UserColumn::EMAIL]);
    $dList->findDoubles([UserColumn::PHONE]);
    $dList->findDoubles([UserColumn::CARD]);
    $result = $dList->getResult();
    echo "ID,PARENT_ID\n";
    foreach ($result as $user) {
        echo sprintf("%d,%d\n", $user->getId(), $user->getParent());
    }
}

getFile();
