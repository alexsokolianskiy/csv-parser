<?php

namespace Alex\CsvParser\Services\Parsers;

use Alex\CsvParser\Models\User;

interface UserParserInterface
{
    public function getUsers(): array;
    public function rowToUser(array $row): User;
}
