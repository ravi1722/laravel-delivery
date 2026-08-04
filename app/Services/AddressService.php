<?php

namespace App\Services;

use App\Contracts\AddressServiceInterface;
use App\Repositories\AddressRepository;

class AddressService implements AddressServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(private AddressRepository $addressRepository)
    {
        //
    }

    public function getUserAddresses(int $userId): mixed
    {
        return $this->addressRepository->getUserAddresses($userId);
    }

    public function createAddress(array $data): mixed
    {
        return $this->addressRepository->createAddress($data);
    }

    public function updateAddress(int $id, array $data): mixed
    {
        $address = $this->addressRepository->getAddressById($id);

        return $address->update($data);
    }

    public function deleteAddress(int $id): bool
    {
        $address = $this->addressRepository->getAddressById($id);
        $this->addressRepository->updateDefaultOther($id, $address->user_id);

        return $address->delete();
    }

    public function setDefault(int $id): mixed
    {
        return $this->addressRepository->setDefault($id);
    }
}
