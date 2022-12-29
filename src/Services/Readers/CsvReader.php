<?php

namespace Alex\CsvParser\Services\Readers;

use Error;

class CsvReader implements DataReaderInterface
{
    public function __construct(
        private string $filepath,
        private int $maxColumnLength = 100
    ) {
    }
    public function readColumnNames(): array
    {
        $handle = fopen($this->filepath, "r");
        if (!$handle) {
            throw new Error('Error read file');
        }
        $data = fgetcsv($handle, $this->maxColumnLength);
        fclose($handle);
        return $data;
    }

    public function readData(): array
    {
        $rowData = [];
        $row = 0;
        $handle = fopen($this->filepath, "r");
        if (!$handle) {
            throw new Error('Error read file');
        }
        //skip 1st row
        fgetcsv($handle, $this->maxColumnLength);
        while (($data = fgetcsv($handle, $this->maxColumnLength)) !== false) {
            $columns = count($data);
            for ($column = 0; $column < $columns; $column++) {
                $rowData[$row][$column] = $data[$column];
            }
            $row++;
        }
        fclose($handle);

        return $rowData;
    }
}
