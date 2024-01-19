<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeCustomer;
use App\Models\Ad;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{

    public function add_form()
    {
        try {
            return view('customer.add');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function show()
    {
        try {
            $customers = Customer::orderBy('id', 'desc')->paginate(5);
            return view('customer.list', compact('customers'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function store(Request $request)
    {
        // try {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:' . Customer::class],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'numeric', 'unique:' . Customer::class],
        ]);

        $customer = Customer::create($request->all());
        Mail::to($customer->email)->send(new WelcomeCustomer($customer));
        return redirect('/admin/dashboard/customer_list');
        // } catch (\Throwable $th) {
        //     $th->getMessage();
        // }
    }
    public function delete($id)
    {
        try {

            $customer = Customer::findorFail($id);

            $ads = Ad::where('customer', $customer->phone)->get();
            $invoices = Invoice::where('customer', $customer->phone)->get();
            foreach ($invoices as $invoice) {
                $invoice->delete();
            }
            foreach ($ads as $ad) {
                $ad->delete();
            }

            $customer->delete();

            return redirect('/admin/dashboard/customer_list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email',],
                'phone' => ['required', 'string'],
                'address' => ['required', 'string', 'max:255'],
            ]);
            $customer->update($request->all());

            return redirect('/admin/dashboard/customer_list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update_form($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return view('customer.update', compact('customer'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $customers = Customer::where('name', 'like', '%' . $request->search . '%')->paginate(5);

            return view('customer.list', compact('customers'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
