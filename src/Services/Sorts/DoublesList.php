<?php

namespace Alex\CsvParser\Services\Sorts;

use Alex\CsvParser\Models\User;
use Alex\CsvParser\Enums\UserColumn;

class DoublesList
{
    private array $sorted;

    public function __construct(
        private array $data,
    ) {
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
        $dataCopy = $this->data;
        usort($dataCopy, $this->buildCompareMethod($columns));
        $this->sorted = $dataCopy;
    }

    public function findDoubles($columns = []): void
    {
        $this->makeSortBy($columns);
        $this->setParent($columns);
        $this->data = $this->sorted;
    }

    public function getResult(): array
    {
        usort($this->sorted, function (User $first, User $second) {
            return $first->getId() <=> $second->getId();
        });
        $this->setNullToSelf();
        return $this->sorted;
    }

    private function setParent($columns = [])
    {
        echo sprintf(
            "find doubles for columns %s\n",
            implode(',', array_map(fn ($col) => $col->name, $columns))
        );
        for ($i = 0; $i < count($this->sorted); $i++) {
            $duplicatesCount = 0;
            $dIndex = $i;
            while ($this->getNext($dIndex) && $this->isDuplicate($this->sorted[$i], $this->getNext($dIndex), $columns)) {
                $dIndex++;
                $duplicatesCount++;
            }
            if ($duplicatesCount > 1) {
                echo sprintf(
                    "trying to set parent duplicates, with columns %s and i: %d\n",
                    implode(',', array_map(fn ($col) => $col->name, $columns)),
                    $i
                );
                for ($j = $i; $j < $i + $duplicatesCount; $j++) {
                    if ($this->sorted[$j]->getParent() != null) {
                        continue;
                    }
                    echo sprintf(
                        "trying to set parent id[%d] for item with id[%d], with columns %s\n",
                        $this->sorted[$i]->getId(),
                        $this->sorted[$j]->getId(),
                        implode(',', array_map(fn ($col) => $col->name, $columns))
                    );
                    $parent = $this->sorted[$i];
                    $id = $parent->getParent() ?? $parent->getId();
                    $this->sorted[$j]->setParent($id);
                }
            }
            echo sprintf(
                "doubles %d for columns %s\n",
                $duplicatesCount,
                implode(',', array_map(fn ($col) => $col->name, $columns))
            );
            $i = $dIndex - 1;
        }
    }

    private function setNullToSelf()
    {
        $this->sorted = array_map(function (User $usr) {
            if (is_null($usr->getParent())) {
                $usr->setParent($usr->getId());
            }
            return $usr;
        }, $this->sorted);
    }

    private function isDuplicate(User $item, User $next, array $columns): bool
    {
        foreach ($columns as $column) {
            $getterName = $this->getColumnGetterMap($column->name);
            $optionsFirst[] = $item->$getterName();
            $optionsSecond[] = $next->$getterName();
        }

        return $optionsFirst == $optionsSecond;
    }

    private function getNext(int $i): ?User
    {
        $count = count($this->sorted);
        if ($i < $count) {
            return $this->sorted[$i];
        }

        return null;
    }
}
