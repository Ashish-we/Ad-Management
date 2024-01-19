<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use PhpParser\Node\Stmt\TryCatch;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            return view('admin.dashboard');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function login_form()
    {
        try {
            return view('admin.login');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function register_form()
    {
        try {
            return view('admin.register');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function register(Request $request)
    {
        try {
            // dd($request->name);
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Admin::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => ['required', 'string', 'max:255'],
            ]);

            $user = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
            ]);
            Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'));


            return redirect('admin/dashboard');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
                'password' => ['required', 'string', 'max:255'],
            ]);
            Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'));

            return redirect('admin/dashboard');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function logout()
    {
        try {
            Auth::guard('admin')->logout();
            return redirect('/');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
