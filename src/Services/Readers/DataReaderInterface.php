<?php

namespace Alex\CsvParser\Services\Readers;

interface DataReaderInterface
{
    public function readColumnNames(): array;
    public function readData(): array;
}
