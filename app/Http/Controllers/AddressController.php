<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->get();
        return view('profile.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('profile.addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'is_primary' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_primary'])) {
            Auth::user()->addresses()->update(['is_primary' => false]);
            $validated['is_primary'] = true;
        } else {
            $validated['is_primary'] = false;
        }

        // If it's the first address, make it primary automatically
        if (Auth::user()->addresses()->count() == 0) {
            $validated['is_primary'] = true;
        }

        Auth::user()->addresses()->create($validated);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        return view('profile.addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'is_primary' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_primary'])) {
            Auth::user()->addresses()->update(['is_primary' => false]);
            $validated['is_primary'] = true;
        } else {
            $validated['is_primary'] = false;
        }

        // Ensure at least one primary address
        if (!$validated['is_primary'] && $address->is_primary && Auth::user()->addresses()->count() > 1) {
            $firstOther = Auth::user()->addresses()->where('id', '!=', $address->id)->first();
            if ($firstOther) {
                $firstOther->update(['is_primary' => true]);
            }
        } else if (Auth::user()->addresses()->count() == 1) {
            $validated['is_primary'] = true;
        }

        $address->update($validated);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);

        $address->delete();

        // Reassign primary if needed
        if ($address->is_primary) {
            $firstOther = Auth::user()->addresses()->first();
            if ($firstOther) {
                $firstOther->update(['is_primary' => true]);
            }
        }

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil dihapus.');
    }
}
