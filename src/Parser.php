<?php

require __DIR__ . '/../vendor/autoload.php';

use Alex\CsvParser\Models\User;
use Alex\CsvParser\Services\Readers\CsvReader;

function getFile()
{
    $csvReader = new CsvReader(__DIR__ . '/../example-small.csv');
    $columns = $csvReader->readColumnNames();
    $data = $csvReader->readData();
    var_dump($data);
}

getFile();
