<?php

namespace Alex\CsvParser\Services\Sorts;

use SplDoublyLinkedList;

class DoublesList
{
    private SplDoublyLinkedList $list;

    public function __construct(
        private array $data,
    ) {
        $this->emptyList();
    }

    private function emptyList(): void
    {
        $this->list = new SplDoublyLinkedList();
    }

    private function makeSortBy(array $columns = []): void
    {
        $this->emptyList();
        foreach ($this->data as $user) {
            // var_dump($user);
        }
    }

    public function findDoubles(array $columns = []): array
    {
        $this->makeSortBy($columns);
        return [];
    }
}
