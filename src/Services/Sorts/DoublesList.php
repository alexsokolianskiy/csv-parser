<?php

namespace Alex\CsvParser\Services\Sorts;

use Alex\CsvParser\Enums\UserColumn;
use SplDoublyLinkedList;
use Alex\CsvParser\Models\User;

class DoublesList
{
    private array $sorted;

    public function __construct(
        private array $data,
    ) {
        $this->emptyList();
    }

    private function emptyList(): void
    {
        $this->sorted = [];
    }

    private function getColumnGetterMap(string $column): ?string
    {
        $map = [
            UserColumn::EMAIL->name => 'getEmail',
            UserColumn::CARD->name => 'getCard',
            UserColumn::PHONE->name => 'getPhone'
        ];

        return $map[$column] ?? null;
    }

    public function buildCompareMethod(array $columns): callable
    {
        return function (User $first, User $second) use ($columns) {
            $optionsFirst = [];
            $optionsSecond = [];
            foreach ($columns as $column) {
                $getterName = $this->getColumnGetterMap($column->name);
                $optionsFirst[] = $first->$getterName();
                $optionsSecond[] = $second->$getterName();
            }
            return $optionsFirst <=> $optionsSecond;
        };
    }

    private function makeSortBy(array $columns = []): void
    {
        $this->emptyList();
        $dataCopy = $this->data;
        usort($dataCopy, $this->buildCompareMethod($columns));
        $this->sorted = $dataCopy;
    }

    public function findDoubles(array $columns = []): array
    {
        $this->makeSortBy($columns);
        return $this->sorted;
    }
}
