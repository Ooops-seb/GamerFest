<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PhoneUpdateController extends Controller
{
    /**
     * Update the user's phone number.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => 'required|string|max:13|unique:users,phone,' . Auth::id(),
        ]);

        $user = $request->user();
        $user->phone = $request->phone;
        $user->save();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
