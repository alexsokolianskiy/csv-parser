<?php

namespace Alex\CsvParser\Services\Parsers;

use Alex\CsvParser\Models\User;
use Alex\CsvParser\Enums\UserColumn;
use Alex\CsvParser\Services\Parsers\UserParserInterface;
use Alex\CsvParser\Services\Readers\DataReaderInterface;

class UserParser implements UserParserInterface
{
    public function __construct(
        private DataReaderInterface $dataReader,
        private ?array $userColumns = null
    ) {
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
            $value = $this->getColumIndexByName($userColumn);
            switch ($userColumn) {
                case UserColumn::ID:
                    $user->setId($value);
                    break;
                case UserColumn::CARD:
                    $user->setCard($value);
                    break;
                case UserColumn::EMAIL:
                    $user->setEmail($value);
                    break;
                case UserColumn::PHONE:
                    $user->setPhone($value);
            }
        }

        return $user;
    }

    private function getColumIndexByName(UserColumn $userColumn): string
    {
        return $this->getColumnNameIndexes()[$userColumn];
    }

    private function getColumnNameIndexes(): array
    {
        if ($this->userColumns) {
            return $this->userColumns;
        }

        $columns = $this->dataReader->readColumnNames();
        $userColumns = [
            UserColumn::ID,
            UserColumn::EMAIL,
            UserColumn::PHONE,
            UserColumn::CARD,
            UserColumn::PARENT_ID
        ];
        array_walk($columns, function ($column, $key) use (&$userColumns) {
            foreach ($userColumns as $userColumn) {
                if ($column == $userColumn->name) {
                    $userColumns[$userColumn] = $key;
                }
            }
        });

        return $this->userColumns = $userColumns;
    }
}
