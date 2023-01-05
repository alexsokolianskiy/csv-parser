<?php

require __DIR__ . '/../vendor/autoload.php';

use Alex\CsvParser\Enums\UserColumn;
use Alex\CsvParser\Services\Parsers\UserParser;
use Alex\CsvParser\Services\Readers\CsvReader;
use Alex\CsvParser\Services\Readers\StreamReader;
use Alex\CsvParser\Services\Sorts\ChainList;
use Alex\CsvParser\Services\Sorts\DoublesList;

function getFile()
{
    // $csvReader = new CsvReader(__DIR__ . '/../example-big.csv');
    // $userParser = new UserParser($csvReader);
    $input_data = file_get_contents("php://stdin");
    $streamReader = new StreamReader($input_data);
    $userParser = new UserParser($streamReader);
    $users = $userParser->getUsers();
    $list = new ChainList($users);
    $result = $list->build();
    echo "ID,PARENT_ID\n";
    foreach ($result as $user) {
        echo sprintf("%d,%d\n", $user->getId(), $user->getParent());
    }
}

getFile();
