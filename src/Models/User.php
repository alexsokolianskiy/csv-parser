<?php

namespace Alex\CsvParser\Models;

class User
{
    private int $id;
    private string $email;
    private string $card;
    private string $phone;
    private ?int $parent = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(?int $parent): void
    {
        $this->parent = $parent;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCard(): string
    {
        return $this->card;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function setCard(string $card): void
    {
        $this->card = $card;
    }
}
