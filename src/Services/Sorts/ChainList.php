<?php

namespace Alex\CsvParser\Services\Sorts;

class ChainList
{
    private array $nodes = [];
    private array $doubles = [];
    public function __construct(
        private array $data,
    ) {
    }

    public function build()
    {
        foreach ($this->data as $user) {
            foreach ($this->data as $compareUser) {
                if (
                    $user->getEmail() == $compareUser->getEmail()
                    || $user->getPhone() == $compareUser->getPhone()
                    || $user->getCard() == $compareUser->getCard()
                ) {
                    $min = $compareUser->getId();
                    if (!isset($this->nodes[$min])) {
                        $this->nodes[$min] = [];
                    }
                    $this->nodes[$min][$user->getId()] = $user;
                }
            }
        }

        $map = $this->buildMap();
        foreach ($this->data as $user) {
            $user->setParent($map[$user->getId()]);
        }
        return $this->data;
    }

    public function deepSearch($id, $path = [])
    {
        $path[] = $id;
        foreach ($this->doubles[$id] as $value) {
            if (!in_array($value, $path)) {
                $path = array_merge($path, $this->deepSearch($value, $path));
            }
        }
        return array_unique($path);
    }

    public function buildMap(): array
    {
        foreach ($this->nodes as $id => $values) {
            foreach ($values as $user) {
                $this->doubles[$id][] = $user->getId();
            }
        }

        $map = [];
        foreach ($this->doubles as $key => $values) {
            $path = $this->deepSearch($key);
            $map[$key] = min($path);
        }

        return $map;
    }
}
