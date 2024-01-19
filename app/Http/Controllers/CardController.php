<?php

namespace App\Http\Controllers;

use App\Exports\ExcelExport;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CardController extends Controller
{
    public function add_form()
    {
        try {
            return view('card.add');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function show()
    {
        try {
            $cards = Card::orderBy('id', 'desc')->paginate(15);
            return view('card.list', compact('cards'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function store(Request $request)
    {
        // try {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'unique:' . Card::class],
            'USD' => ['required'],
        ]);

        $cards = Card::create($request->all());

        // return redirect('/admin/dashboard/card_list');
        return redirect()->route('all_in_one');
        // } catch (\Throwable $th) {
        //     $th->getMessage();
        // }
    }
    public function delete($id)
    {
        try {
            $card = Card::findorFail($id);
            DB::table('card_credit_info')->where('card_id', $id)->delete();
            DB::table('card_debit_info')->where('card_id', $id)->delete();
            // $credit_history = DB::table('card_credit_info')->where('card_id', $id)->get();
            // foreach ($credit_history as $credit) {
            //     $credit->delete();
            // }
            // $debit_history = DB::table('card_debit_info')->where('card_id', $id)->get();
            // foreach ($debit_history as $debit) {
            //     $debit->delete();
            // }
            $card->delete();

            return redirect('/admin/dashboard/card_list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $card = Card::findOrFail($id);
            if ($card->card_number == $request->card_number) {
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'card_number' => ['required', 'string'],
                    'USD' => ['required', 'numeric'],
                ]);
            } else {
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'card_number' => ['required', 'string', 'unique:' . Card::class],
                    'USD' => ['required', 'numeric'],
                ]);
            }
            $card->update($request->all());

            return redirect('/admin/dashboard/card_list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update_form($id)
    {
        try {
            $card = Card::findOrFail($id);
            return view('card.update', compact('card'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $cards = Card::where('name', 'like', '%' . $request->search . '%')->paginate(15);

            return view('card.list', compact('cards'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function summary()
    {
        try {

            $summary = Card::select(
                DB::raw('SUM(USD) as totalUSD'),
            )->first();
            // dd($summary);

            return view('card.card-details', compact('summary'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function all_in_one()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $summary = Card::select(
            DB::raw('SUM(USD) as totalUSD'),
        )->first();
        $cards = Card::orderBy('USD', 'desc')->get();
        $credits = DB::table('card_credit_info')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
        $debits = DB::table('card_debit_info')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

        return view('card.all_in_one', compact('cards', 'credits', 'debits', 'summary'));
    }

    public function exportToExcel()
    {
        return Excel::download(new ExcelExport, 'exported_data.xlsx');
    }
}
