<?php

namespace App\Repositories;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getUserAddresses(int $userId)
    {
        return Address::where("user_id", $userId)->orderByDesc('is_default')
            ->orderByDesc('created_at')->get();
    }

    public function createAddress(array $data)
    {
        return DB::transaction(function () use ($data) {

            // If this is set as default, unset others
            if (!empty($data['is_default'])) {
                Address::where('user_id', $data['user_id'])->update(['is_default' => false]);
            }

            // First address is always default
            $isFirst = !Address::where('user_id', $data['user_id'])->exists();

            return Address::create([...$data, 'is_default' => $isFirst || !empty($data['is_default'])]);
        });
    }

    public function getAddressById(int $id)
    {
        return Address::findOrFail($id);
    }

    public function updateDefaultOther(int $id,int $user_id): void
    {
        // if delete the default address, update default for other address if it is there. 
        $otherAddress = Address::where('id', '!=', $id)->where('user_id', $user_id)->latest()->first();
        if ($otherAddress) $otherAddress->update(['is_default' => true]);
    }

    public function setDefault(int $id)
    {
        return DB::transaction(function () use ($id) {
            $address = $this->getAddressById($id);

            Address::where('user_id', $address->user_id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);
            return $address->fresh();
        });
    }
}
