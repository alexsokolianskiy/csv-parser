<?php

require __DIR__ . '/../vendor/autoload.php';

use Alex\CsvParser\Enums\UserColumn;
use Alex\CsvParser\Services\Parsers\UserParser;
use Alex\CsvParser\Services\Readers\CsvReader;
use Alex\CsvParser\Services\Sorts\DoublesList;

function getFile()
{
    $csvReader = new CsvReader(__DIR__ . '/../example-small.csv');
    $userParser = new UserParser($csvReader);
    $users = $userParser->getUsers();
    $dList = new DoublesList($users);
    $result = $dList->findDoubles([UserColumn::EMAIL]);
    var_dump($result);
}

getFile();
