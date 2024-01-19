<?php

namespace App\Http\Controllers;

use App\Mail\AdReceipt;
use App\Mail\AdUpdateRecipt;
use App\Models\Ad;
use App\Models\Card;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Other_Exp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AdController extends Controller
{

    public function ad_form()
    {

        try {
            return view('admin.ads');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    public function showAds()
    {
        // try {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $ads = Ad::whereBetween('created_at', [$startOfMonth, $endOfMonth])->orderBy('id', 'desc')->paginate(15);
        // dd($paused_amount);
        return view('admin.ads_list', compact('ads'));
        // } catch (\Throwable $th) {
        //     $th->getMessage();
        // }
    }
    public function showCompleteAds()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $ads = Ad::where('created_at', '<', $startOfMonth)->where('is_complete', 1)->orderBy('id', 'desc')->paginate(10);
            return view('admin.ads_complete_list', compact('ads'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function storeAd(Request $request)
    {
        try {
            $request->validate([
                'customer' => 'required',
                'USD' => 'required|numeric',
                'Rate' => 'required|numeric',
                'NRP' => 'required|numeric',
                'Ad_Account' => 'required',
                'Payment' => 'required',
                'Duration' => 'required',
                'Quantity' => 'required|integer',
                'Status' => 'required',
                'Ad_Nature_Page' => 'required',
                'admin' => 'required',
            ]);
            // $startDate = Carbon::parse($request->start_date)->format('y-m-d');
            // $endDate = Carbon::parse($request->end_date)->format('y-m-d');
            // dd($startDate);
            // Create a new ad instance
            $customer = Customer::where('phone', $request->customer)->first();
            if ($customer == null) {
                return redirect()->route('customer.add')->with('status', "Customer with the phone number $request->customer entered by you dosenot exist!, create new customer with this phone number!");
            }
            $ad = Ad::create([
                'customer' => $request->customer,
                'USD' => $request->USD,
                'Rate' => $request->Rate,
                'NRP' => $request->NRP,
                'Ad_Account' => $request->Ad_Account,
                'Payment' => $request->Payment,
                'Duration' => $request->Duration,
                'Quantity' => $request->Quantity,
                'Ad_Nature_Page' => $request->Ad_Nature_Page,
                'Status' => $request->Status,
                'advance' => $request->advance,
                'admin' => $request->admin,
                'is_complete' => 0,
            ]);
            if ($request->Status != "On schedule") {
                Mail::to($customer->email)->send(new AdReceipt($ad));
            }


            return redirect()->route('ads.show')->with('success', 'Ad created successfully');
        } catch (Throwable $th) {
            return $th->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            $ad = Ad::findOrFail($id);

            // You may fetch the necessary data for dropdowns here
            // For example:
            $customers = Customer::all();
            // $admins = Admin::all();
            return view('admin.ads_update', compact('ad', 'customers'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $ad = Ad::findOrFail($id);

            // Validation - you can customize this based on your needs
            // $request->validate([
            //     // Validation rules here
            // ]);

            // Update the ad

            if ($request->Payment != "Baki" && $request->Payment != "Refunded" && $request->Payment != "Overpaid") {
                $advance = null;
            } else {
                $advance = $request->advance;
            }
            $ad->update([
                'customer' => $ad->customer,
                'USD' => $request->USD,
                'Rate' => $request->Rate,
                'NRP' => $request->NRP,
                'Ad_Account' => $request->Ad_Account,
                'Payment' => $request->Payment,
                'Duration' => $request->Duration,
                'Quantity' => $request->Quantity,
                'Ad_Nature_Page' => $request->Ad_Nature_Page,
                'Status' => $request->Status,
                'advance' => $advance,
                'admin' => $request->admin,
                'is_complete' => $ad->is_complete,
            ]);
            // $customer = Customer::where('phone', $ad->customer)->first();
            // if ($request->Status != "On schedule") {
            //     Mail::to($customer->email)->send(new AdReceipt($ad));
            // }


            // Mail::to($customer->email)->send(new AdUpdateRecipt($id));

            return redirect()->route('ads.show')->with('success', 'Ad updated successfully');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            // dd($id);
            $ad = Ad::findOrFail($id);

            // Delete the ad
            $ad->delete();
            // dd($ad);
            return redirect()->route('ads.show')->with('success', 'Ad deleted successfully');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }


    public function search(Request $request)
    {
        try {
            $query = Ad::query();

            // Filter by customer contact number
            if ($request->has('customer')) {
                $query->where('customer', 'like', '%' . $request->customer . '%')->orderBy('id', 'DESC');
            }

            // Filter by date range
            if ($request->start_date != 0 && $request->end_date != 0) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59'])->orderBy('id', 'DESC');
            }

            // Fetch the filtered ads
            $ads = $query->paginate(10);

            return view('admin.ads_list', compact('ads'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    public function search_ad_complete(Request $request)
    {
        try {
            $query = Ad::where('is_complete', 1);

            // Filter by customer contact number
            if ($request->has('customer')) {
                $query->where('customer', 'like', '%' . $request->customer . '%')->orderBy('id', 'DESC');
            }

            // Filter by date range
            if ($request->start_date != 0 && $request->end_date != 0) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59'])->orderBy('id', 'DESC');
            }

            // Fetch the filtered ads
            $ads = $query->paginate(10);

            return view('admin.ads_complete_list', compact('ads'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    // for dashboard
    public function summarydashboard()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $monthlyAdIncomeSummaries = Ad::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->select(
                    DB::raw('SUM(USD) as totalUSD'),
                    DB::raw('SUM(NRP) as totalNRP'),
                    // DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )->first();
            $monthlyExp = Other_Exp::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->select(
                    DB::raw('SUM(amount) as totalAmt'),
                )
                ->first();
            // ->groupBy('monthYear')
            // ->paginate(2);
            $monthlyClientSummaries = Client::whereBetween('created_at', [$startOfMonth, $endOfMonth])->select(
                DB::raw('SUM(USD) as totalUSD'),
                DB::raw('SUM(NRP) as totalNRP'),
                // DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
            )->first();


            // ->groupBy('monthYear')
            // ->paginate(1);
            // ->get();
            $Cardsummary = Card::select(
                DB::raw('SUM(USD) as totalUSD'),
            )->first();
            $monthlyCreditSummaries = DB::table('card_credit_info')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->select(
                    DB::raw('SUM(USD) as totalUSD'),
                    // DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )

                ->get();
            // ->groupBy('monthYear')
            // ->paginate(1);

            $monthlyDebitSummaries = DB::table('card_debit_info')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->select(
                    DB::table('card_debit_info')->raw('SUM(USD) as totalUSD'),
                    // DB::table('card_debit_info')->raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )
                ->get();
            // ->groupBy('monthYear')
            // ->paginate(1);


            return view('admin.dashboard', compact('monthlyAdIncomeSummaries', 'monthlyClientSummaries', 'Cardsummary', 'monthlyCreditSummaries', 'monthlyDebitSummaries', 'startOfMonth', 'monthlyExp'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }


    public function summary()
    {
        // Assuming your model is named Ad and has a 'created_at' field
        // $ads = Ad::where('is_complete', '>', 0)->get();
        // $ads_paid = Ad::where('is_complete', '>', 0)->where('Status', 'Paid')->get();
        // $ads_due = Ad::where('is_complete', '>', 0)->where('Status', '!=', 'Paid')->get();
        // Initialize arrays to store monthly summaries
        // $monthlySummaryUSD = [];
        // $monthlySummaryNRP = [];

        // foreach ($ads as $ad) {
        //     $monthYear = date('Y-m', $ad->end_time);

        //     if (!isset($monthlySummaryUSD[$monthYear])) {
        //         $monthlySummaryUSD[$monthYear] = 0;
        //         $monthlySummaryNRP[$monthYear] = 0;
        //     }

        //     $monthlySummaryUSD[$monthYear] += $ad->USD;
        //     $monthlySummaryNRP[$monthYear] += $ad->NRP;
        // }
        try {
            $monthlySummaries = Ad::select(
                DB::raw('SUM(USD) as totalUSD'),
                DB::raw('SUM(NRP) as totalNRP'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
            )
                ->groupBy('monthYear')
                ->paginate(6);
            $monthlySummaries_paid = Ad::where('is_complete', '>', 0)->where('Status', 'Paid')
                ->select(
                    DB::raw('SUM(USD) as totalUSD'),
                    DB::raw('SUM(NRP) as totalNRP'),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )
                ->groupBy('monthYear')
                ->paginate(6);
            $monthlySummaries_due = Ad::where('is_complete', '>', 0)->where('Status', '!=', 'Paid')
                ->select(
                    DB::raw('SUM(USD) as totalUSD'),
                    DB::raw('SUM(NRP) as totalNRP'),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )
                ->groupBy('monthYear')
                ->paginate(6);


            return view('admin.ads_summary', compact('monthlySummaries', 'monthlySummaries_due', 'monthlySummaries_paid'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function thisDay()
    {
        try {
            $startOfDay = Carbon::now()->startOfDay();
            $endOfDay = Carbon::now()->endOfDay();

            $clientsThisDay = Ad::whereBetween('created_at', [$startOfDay, $endOfDay])
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('admin.ads_list', ['ads' => $clientsThisDay]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function yesterday()
    {
        try {
            $startOfYesterday = Carbon::yesterday()->startOfDay();
            $endOfYesterday = Carbon::yesterday()->endOfDay();

            $clientsYesterday = Ad::whereBetween('created_at', [$startOfYesterday, $endOfYesterday])
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('admin.ads_list', ['ads' => $clientsYesterday]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function thisWeek()
    {
        try {

            // Set Sunday as the start of the week
            Carbon::setWeekStartsAt(Carbon::SUNDAY);

            // Get the start of the week
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            // dd($startOfWeek, $endOfWeek);
            $adsThisWeek = Ad::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('admin.ads_list', ['ads' => $adsThisWeek]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function thisMonth()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $adsThisMonth = Ad::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('admin.ads_list', ['ads' => $adsThisMonth]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    public function thisWeek_complete()
    {
        try {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $adsThisWeek = Ad::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->orderBy('id', 'desc')
                ->where('is_complete', 1)
                ->paginate(10);

            return view('admin.ads_complete_list', ['ads' => $adsThisWeek]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function thisMonth_complete()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $adsThisMonth = Ad::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->orderBy('id', 'desc')
                ->where('is_complete', 1)
                ->paginate(10);

            return view('admin.ads_complete_list', ['ads' => $adsThisMonth]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function thisDay_complete()
    {
        try {
            $startOfDay = Carbon::now()->startOfDay();
            $endOfDay = Carbon::now()->endOfDay();

            $clientsThisDay = Ad::whereBetween('created_at', [$startOfDay, $endOfDay])
                ->orderBy('id', 'desc')
                ->where('is_complete', 1)
                ->paginate(10);

            return view('admin.ads_complete_list', ['ads' => $clientsThisDay]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function yesterday_complete()
    {
        try {
            $startOfYesterday = Carbon::yesterday()->startOfDay();
            $endOfYesterday = Carbon::yesterday()->endOfDay();

            $clientsYesterday = Ad::whereBetween('created_at', [$startOfYesterday, $endOfYesterday])
                ->orderBy('id', 'desc')
                ->where('is_complete', 1)
                ->paginate(10);

            return view('admin.ads_complete_list', ['ads' => $clientsYesterday]);
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function email_to_send($id)
    {
        $ad = Ad::findorFail($id);
        $customer = Customer::where('phone', $ad->customer)->first();
        if ($ad->Status != "On schedule") {
            Mail::to($customer->email)->send(new AdReceipt($ad));
        }

        return redirect()->route('ads.show');
    }
}
