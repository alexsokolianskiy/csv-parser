<?php

namespace Alex\CsvParser\Services\Readers;

class StreamReader implements DataReaderInterface
{
    private array $data;
    public function __construct(
        private string $input
    ) {
        $this->data = explode("\n", $this->input);
    }

    public function readColumnNames(): array
    {
        return explode(',', $this->data[0]);
    }

    public function readData(): array
    {
        $rowData = [];

        for ($i = 1; $i < count($this->data) - 1; $i++) {
            $splitted = explode(',', $this->data[$i]);
            for ($j = 0; $j < count($splitted); $j++) {
                $rowData[$i][$j] = is_numeric($splitted[$j]) ? (int) $splitted[$j] : $splitted[$j];
            }
        }

        return $rowData;
    }
}
