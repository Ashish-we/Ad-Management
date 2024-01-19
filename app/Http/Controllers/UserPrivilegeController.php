<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Note;
use App\Models\UserPrivilege;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class UserPrivilegeController extends Controller
{
    public function register_form()
    {
        try {
            return view('admin.user.add');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function show()
    {
        try {
            $super_admin = UserPrivilege::where('full_or_partial', 1)->first();
            if ($super_admin) {
                $users = Admin::where('id', '!=', $super_admin->user_id)->orderBY('id', 'DESC')->paginate(10);
            } else {
                $users = Admin::orderBy('id', 'DESC')->paginate();
            }

            return view('admin.user.list', compact('users'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function register(Request $request)
    {
        // try {
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
        // Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'));
        // Note::create([
        //     'id' => $user->id,
        // ]);
        UserPrivilege::create([
            'user_id' => $user->id,
            'option' => null,
            'full_or_partial' => 0,
        ]);

        return redirect('admin/dashboard/user/list');
        // } catch (\Throwable $th) {
        //     $th->getMessage();
        // }
    }

    public function search(Request $request)
    {

        try {

            $users = Admin::where('name', 'like', '%' . $request->search . '%')->paginate(10);
            return view('admin.user.list', compact('users'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $user = Admin::findorFail($id);
            $user_privilege = UserPrivilege::where('user_id', $user->id)->first();

            $user->delete();
            $user_privilege->delete();

            return redirect()->route('admin.user.show')->with('status', 'success');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            // Retrieve the authenticated admin
            $user = Admin::findorFail($id);
            return view('admin.user.update', compact('user'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        // dd($id);
        // try {
        $user = Admin::findorFail($id);
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:15',
        ]);
        // dd('helllo');
        // Update admin profile
        $admin = auth('admin')->user();
        $admin_ = Admin::findorFail($id);
        $admin_->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'] ? bcrypt($validatedData['password']) : $admin->password,
            'phone' => $validatedData['phone'],
        ]);

        return redirect()->route('admin.user.show')->with('success', 'Profile updated successfully!');
        // } catch (\Throwable $th) {
        //     $th->getMessage();
        // }
    }

    public function privilege($id)
    {
        $user = Admin::findorFail($id);
        $userPrivileges = UserPrivilege::select('option')->where('user_id', $id)->first();
        if ($userPrivileges) {
            $userPrivileges = explode(',', $userPrivileges['option']);
        } else {
            $userPrivileges = [];
        }
        // dd($userPrivileges);
        return view('admin.user.privilege', compact('user', 'userPrivileges'));
    }

    public function privilege_store(Request $request, $id)
    {
        $privilages = UserPrivilege::where('user_id', $id)->first();
        if ($privilages) {
            $privilages->update([
                'user_id' => $id,
                'option' => $request->input('privileges'),
                'full_or_partial' => 0,
            ]);
            return response()->json(['success' => true, 'privillages' => $privilages]);
        } else {
            $privillages = UserPrivilege::create([
                'user_id' => $id,
                'option' => $request->input('privileges'),
                'full_or_partial' => 0,
            ]);
            return response()->json(['success' => true, 'privillages' => $privillages]);
        }
    }
}
