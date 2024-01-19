<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function edit()
    {
        try {
            // Retrieve the authenticated admin
            $admin = Auth('admin')->user();

            return view('admin.profile', compact('admin'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request)
    {
        // try {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . auth('admin')->id(),
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:15',
        ]);
        // dd('helllo');
        // Update admin profile
        $admin = auth('admin')->user();
        $admin_ = Admin::find($admin->id);
        $admin_->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'] ? bcrypt($validatedData['password']) : $admin->password,
            'phone' => $validatedData['phone'],
        ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully!');
        // } catch (\Throwable $th) {
        //     $th->getMessage();
        // }
    }
}
