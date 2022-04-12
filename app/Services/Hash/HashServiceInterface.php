<?php


namespace App\Services\Hash;


interface HashServiceInterface
{
    public function create(string $value) : string;
    public function validate(string $hash, string $value) : bool;
}
