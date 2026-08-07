<?php

namespace App\Contracts;

interface AddressServiceInterface
{
    public function getUserAddresses(int $userId): mixed;
    public function createAddress(array $data): mixed;
    public function updateAddress(int $id, array $data): mixed;
    public function deleteAddress(int $id): bool;
    public function setDefault(int $id): mixed;
}
