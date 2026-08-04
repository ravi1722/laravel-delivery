<?php

namespace App\Http\Controllers\Customer;

use App\Contracts\AddressServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function __construct(private AddressServiceInterface $addressService) {}
    public function index()
    {
        $addresses = $this->addressService->getUserAddresses(Auth::user()->id);
        return view('customer.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'         => 'required|string|max:50',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'required|string|max:100',
            'state'         => 'required|string|max:100',
            'pincode'       => 'required|digits:6',
            'is_default'    => 'nullable|boolean',
        ]);

        $validated['user_id'] = Auth::user()->id;
        $this->addressService->createAddress($validated);

        return back()->with('success', 'Address added successfully!');
    }

    public function update(Request $request,int $id) {
        $this->addressService->updateAddress($id, $request->all());
        return back()->with('success', 'Address updated successfully!');
    }

    public function destroy(int $id) {
        $this->addressService->deleteAddress($id);
        return back()->with('success', 'Address deleted successfully!');
    }

    public function setDefault(int $id) {
        $this->addressService->setDefault($id);
        return back()->with('success', 'Address set as default successfully!');
    }
}
