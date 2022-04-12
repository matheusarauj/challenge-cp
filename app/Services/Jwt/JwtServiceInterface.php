<?php


namespace App\Services\Jwt;


interface JwtServiceInterface
{
    public function create(array $object) : string;

    public function decode($jwt);
}
