<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Client;
use App\Models\Other_Exp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function add_form()
    {
        try {
            return view('client.add');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function show()
    {
        try {
            $clients = Client::orderBy('id', 'desc')->paginate(5);
            return view('client.list', compact('clients'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function store(Request $request)
    {
        // try {
        // dd($request);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'account' => ['required'],
            'USD' => ['required',],
            'Rate' => ['required'],
            'NRP' => ['Required'],
        ]);

        $clients = Client::create($request->all());
        $card = Card::where('card_number', $request->account)->first();
        $card->update([
            'name' => $card->name,
            'card_number' => $card->card_number,
            'USD' => $card->USD + $request->USD,
        ]);
        $admin = Auth('admin')->user();
        DB::table('card_credit_info')->insert([
            'card_id' => $card->id,
            'card_number' => $card->card_number,
            'USD' => $request->USD,
            'by' => "$admin->name ($admin->id)",
            'created_at' => now()
        ]);
        return redirect('/admin/dashboard/client_list');
        // } catch (\Throwable $th) {
        //     $th->getMessage();
        // }
    }
    public function delete($id)
    {
        try {
            $client = Client::findorFail($id);
            $client->delete();

            return redirect('/admin/dashboard/client_list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'account' => ['required'],
                'USD' => ['required',],
                'Rate' => ['required'],
                'NRP' => ['Required'],
            ]);
            $client->update($request->all());

            return redirect('/admin/dashboard/client_list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update_form($id)
    {
        try {
            $client = Client::findOrFail($id);
            return view('client.update', compact('client'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Client::query();
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            if ($request->start_date != 0 && $request->end_date != 0) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59']);
            }
            $clients = $query->paginate(5);
            return view('client.list', compact('clients'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function summary()
    {
        // try {
        // $monthlySummaries = Client::select(
        //     DB::raw('SUM(USD) as totalUSD'),
        //     DB::raw('SUM(NRP) as totalNRP'),
        //     DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
        // )
        //     ->groupBy('monthYear')
        //     ->paginate(6);
        // $monthlyExp = Other_Exp::select(
        //     DB::raw('SUM(amount) as totalAmt'),
        //     DB::raw("DATE_FORMAT(date, '%Y-%m') as monthYear")
        // )->groupBy('monthYear')
        //     ->paginate(6);
        $monthlySummaries = Client::select(
            DB::raw('SUM(USD) as totalUSD'),
            DB::raw('SUM(NRP) as totalNRP'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
        )
            ->groupBy('monthYear')->orderBY('monthYear', 'desc')->get();

        $monthlyExp = Other_Exp::select(
            DB::raw('SUM(amount) as totalAmt'),
            DB::raw("DATE_FORMAT(date, '%Y-%m') as monthYear"),
            DB::raw("'note' as source")
        )
            ->groupBy('monthYear')->orderBY('monthYear', 'desc')->get();
        // $combinedResult = $monthlySummaries->union($monthlyExp)->paginate(6);
        // dd($combinedResult);
        return view('client.summary', compact('monthlySummaries', 'monthlyExp'));
        // } catch (\Throwable $th) {
        //     $th->getMessage();
        // }
    }
    public function thisWeek()
    {
        try {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $clientsThisWeek = Client::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('client.list', ['clients' => $clientsThisWeek]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function thisMonth()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $clientsThisMonth = Client::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('client.list', ['clients' => $clientsThisMonth]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    public function thisDay()
    {
        try {
            $startOfDay = Carbon::now()->startOfDay();
            $endOfDay = Carbon::now()->endOfDay();

            $clientsThisDay = Client::whereBetween('created_at', [$startOfDay, $endOfDay])
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('client.list', ['clients' => $clientsThisDay]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function yesterday()
    {
        try {
            $startOfYesterday = Carbon::yesterday()->startOfDay();
            $endOfYesterday = Carbon::yesterday()->endOfDay();

            $clientsYesterday = Client::whereBetween('created_at', [$startOfYesterday, $endOfYesterday])
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('client.list', ['clients' => $clientsYesterday]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
