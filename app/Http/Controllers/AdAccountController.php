<?php

namespace App\Http\Controllers;

use App\Models\Ad_Account;
use Illuminate\Http\Request;

class AdAccountController extends Controller
{
    public function add_form()
    {
        try {
            return view('ad_account.add');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function show()
    {
        try {
            $accounts = Ad_Account::orderBy('id', 'desc')->paginate(5);
            return view('ad_account.list', compact('accounts'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $account = Ad_Account::create($request->all());

            return redirect('/admin/dashboard/ad_accounts');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    public function delete($id)
    {
        try {
            $account = Ad_Account::findorFail($id);
            $account->delete();

            return redirect('/admin/dashboard/ad_accounts');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $account = Ad_Account::findOrFail($id);
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
            $account->update($request->all());

            return redirect('/admin/dashboard/ad_accounts');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update_form($id)
    {
        try {
            $account = Ad_Account::findOrFail($id);
            return view('ad_account.update', compact('account'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $accounts = Ad_Account::where('name', 'like', '%' . $request->search . '%')->paginate(5);

            return view('ad_account.list', compact('accounts'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
