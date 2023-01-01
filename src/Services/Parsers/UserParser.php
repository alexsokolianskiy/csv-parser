<?php

namespace Alex\CsvParser\Services\Parsers;

use Alex\CsvParser\Models\User;
use Alex\CsvParser\Enums\UserColumn;
use Alex\CsvParser\Services\Parsers\UserParserInterface;
use Alex\CsvParser\Services\Readers\DataReaderInterface;

class UserParser implements UserParserInterface
{
    private ?array $userColumns;

    public function __construct(
        private DataReaderInterface $dataReader,
    ) {
        $this->userColumns = null;
    }

    public function getUsers(): array
    {
        $users = [];
        $data = $this->dataReader->readData();
        foreach ($data as $row) {
            $users[] = $this->rowToUser($row);
        }

        return $users;
    }

    public function rowToUser(array $row): User
    {
        $user = new User();
        foreach (UserColumn::cases() as $userColumn) {
            $value = $this->getColumIndexByName($userColumn->name);
            if (is_null($value)) {
                continue;
            }

            switch ($userColumn) {
                case UserColumn::ID:
                    $user->setId($row[$value]);
                    break;
                case UserColumn::CARD:
                    $user->setCard($row[$value]);
                    break;
                case UserColumn::EMAIL:
                    $user->setEmail($row[$value]);
                    break;
                case UserColumn::PHONE:
                    $user->setPhone($row[$value]);
                    break;
            }
        }

        return $user;
    }

    private function getColumIndexByName(string $userColumn): ?string
    {
        $indexes = $this->getColumnNameIndexes();

        return isset($indexes[$userColumn]) ? $indexes[$userColumn] : null;
    }

    private function getColumnNameIndexes(): array
    {
        if ($this->userColumns) {
            return $this->userColumns;
        }

        $columns = $this->dataReader->readColumnNames();
        $userColumns = [
            UserColumn::ID->name,
            UserColumn::EMAIL->name,
            UserColumn::PHONE->name,
            UserColumn::CARD->name
        ];
        array_walk($columns, function ($column, $key) use (&$userColumns) {
            foreach ($userColumns as $userColumn) {
                if ($column == $userColumn) {
                    $userColumns[$userColumn] = $key;
                }
            }
        });

        return $this->userColumns = $userColumns;
    }
}
