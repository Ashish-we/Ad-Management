<?php

use App\Models\Ad;
use App\Models\Client;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$startOfMonth = Carbon::now()->startOfMonth();
$endOfMonth = Carbon::now()->endOfMonth();
$customers = DB::select('select * from customers');
$paused_amounts = DB::table('balance_rejects')->select('USD')->get();
$paused_amount = 0.0;
foreach ($paused_amounts as $amount) {
    $paused_amount = $paused_amount + $amount->USD;
}
$incomes = Ad::whereBetween('created_at', [$startOfMonth, $endOfMonth])->select('USD')->get();
$expences = Client::whereBetween('created_at', [$startOfMonth, $endOfMonth])->select('USD')->get();
$rev = 0;
$expp = 0;
foreach ($incomes as $income) {
    $rev = $income['USD'] + $rev;
    // dd($income);
}
foreach ($expences as $exp) {
    $expp = $exp['USD'] + $expp;
}
$to_be_load = $rev - $expp;
?>

@extends('admin.layout.layout')

@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        overflow-y: none !important;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Dropdown container styles */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Dropdown button styles */
    .dropdown-button {
        background-color: #3498db;
        color: #fff;
        padding: 8px 16px;
        border: none;
        cursor: pointer;
    }

    /* Dropdown content (hidden by default) */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    /* Dropdown items */
    .dropdown-item {
        padding: 12px 16px;
        display: block;
        color: #333;
        text-decoration: none;
    }

    /* Change color on hover */
    .dropdown-item:hover {
        background-color: #ddd;
    }

    @media screen and (max-width:700px) {
        .overflow-mobile {
            overflow-x: scroll;
        }
    }
</style>
<style>
    /* Style for the dropdown */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Style for the dropdown button */
    .dropdown-btn {
        background-color: gray;
        color: #fff;
        padding: 7px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    /* Style for the dropdown content */
    .dropdown-menu {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    /* Style for dropdown items */
    .dropdown-item {
        padding: 12px 16px;
        display: block;
        text-decoration: none;
        color: #333;
    }

    /* Style for dropdown items on hover */
    .dropdown-item:hover {
        background-color: #3498db;
        color: #fff;
    }
</style>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .popup {
        display: none;
        position: fixed;
        /* top: 50%;
        left: 50%;
        transform: translate(-50%, -50%); */
        right: 0;
        top: 0;
        background-color: #f1f1f1;
        padding: 20px;
        border: 1px solid #ccc;
        z-index: 9999;
        height: 100%;
    }

    .popup-content {
        max-width: 1000px;
        min-width: 1000px;
        margin: 0 auto;
    }

    textarea {
        width: 100%;
        height: 200px;
        margin-bottom: 10px;
    }

    .dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        z-index: 9999;
        top: 100%;
        /* Adjust this value to position it below the button */
        left: 0;
    }
</style>
<div class="container-fluid">

    <div class="card" style="width: 100%;">
        <div class="card-header" style="display: inline-flex;">
            <div class="heading-div" style="width: 50%; display:flex;">
                <h6 class="btn btn-primary" style="margin-left:20px; margin-top:5px; padding:10px!important; border-radius: 2px; font-weight:bold;">Paused Amount :$ {{$paused_amount}} </h6>
                <h6 class="btn btn-primary" style="margin-left:20px; margin-top:5px; padding:10px!important; border-radius: 2px; font-weight:bold;">Amount To be load:$ {{$to_be_load}}</h6>
            </div>
            <div class="button-div" style="width: 50%; display:flex; justify-content:right;">
                <a class="btn btn-primary mr-3" style="height: 38px;" href="{{route('customer.add')}}">Add Customer</a>
                <div id="addnew_btn" style="display: block;">
                    <button class="btn" style="display: inline-flex;background-color: #26495c!important;color:white;" onclick="addRow()">AddNew</button>
                </div>
                <div class=" dropdown" onclick="toggleDropdown()" style=" margin-left:2%;">
                    <!-- <br> -->
                    <button class="btn dropdown-btn;" style="background-color:#c4a35a!important;">Option</button>
                    <div class="dropdown-menu" id="myDropdown">
                        <a href="{{ route('ads.yesterday') }} " class="dropdown-item">Yesterday</a>
                        <a href="{{ route('ads.this_day') }} " class="dropdown-item">Today</a>
                        <a href="{{ route('ads.this_week') }} " class="dropdown-item">This Week</a>
                        <a href="{{ route('ads.this_month') }} " class="dropdown-item">This Month</a>
                    </div>
                </div>
                <button class="ml-3 btn sm btn-primary" style="height: 38px;" id="noteButton">Open Note</button>
            </div>

            <div id="notePopup" class="popup">
                <div class="popup-content">
                    <span class="close" id="closeButton">&times;</span>
                    <textarea id="noteInput" placeholder="Write your note..."></textarea>
                    <table>
                        <thead>
                            <th style="min-width: 150px;max-width:150px">Customer</th>
                            <th>USD</th>
                            <th>Remarks</th>
                            <th>xyz</th>
                        </thead>
                        <tbody>
                            <?php
                            $x = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            ?>
                            <div id="myInputsContainer">
                                @foreach($x as $item)
                                <tr id="{{$item}}" class="items">
                                    <td><input name="customer[{{$item}}]" type="text"></td>
                                    <td><input name="USD[{{$item}}]" type="number" step="0.01"></td>
                                    <td><input name="Remarks[{{$item}}]" type="text"></td>
                                    <td><input name="xyz[{{$item}}]" type="text"></td>
                                </tr>
                                @endforeach
                            </div>
                        </tbody>
                    </table>
                    <button id="saveButton">Save Note</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div id="add_new" style="display: none;">
                <form action="{{ route('storeAd') }}" method="POST">
                    @csrf
                    <table>
                        <thead>
                            <tr>
                                <th style="min-width: 200px;"> Customer </th>
                                <th style="min-width: 100px;">USD</th>
                                <th style="min-width: 100px;">Rate</th>
                                <th style="min-width: 100px;">NRP</th>
                                <th style="min-width: 200px;">Ad Account</th>
                                <th style="min-width: 150px;">Payment Method</th>
                                <th style="min-width: 50px;">Duration(in Days)</th>
                                <th style="min-width: 50px;">Quantity</th>
                                <th style="min-width: 100px;">Status</th>
                                <th style="min-width: 100px;">Baki</th>
                                <th style="min-width: 150px;">Ad Nature/Page</th>
                                <th><button class="btn btn-danger" onclick="close_()">Cancel</button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="ad__table">
                                <td><input class="form-control" type="number" id="customer" name="customer" required></td>
                                <td><input class="form-control" type="number" id="USD" step="0.01" name="USD" required></td>
                                <td><input class="form-control" type="number" id="Rate" step="0.01" name="Rate" required></td>
                                <td><input class="form-control" type="number" id="NRP" name="NRP" required></td>
                                <td><input class="form-control" type="text" id="Ad_Account" name="Ad_Account" required></td>
                                <td><select class="form-control" id="Payment" name="Payment" required onchange="toggleNewbakiField()">
                                        <option value="Pending">Pending</option>
                                        <option value="Paused">Paused</option>
                                        <option value="FPY Received">FPY Received</option>
                                        <option value="eSewa Received">eSewa Received</option>
                                        <option value="Baki">Baki</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Refunded">Refunded</option>
                                        <option value="Cancelled ">Cancelled </option>
                                        <option value="Overpaid">Overpaid </option>
                                        <option value="PV Adjusted">PV Adjusted </option>
                                        <option value="Informed">Informed</option>
                                    </select></td>
                                <td><input type="number" id="duration" name="Duration" class="form-control" required></td>
                                <td><input type="number" class="form-control" id="Quantity" name="Quantity" required></td>
                                <td><select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                                        <option value="New">New</option>
                                        <option value="Extend">Extend</option>
                                        <option value="Both">Both</option>
                                        <option value="On schedule">On schedule</option>
                                    </select></td>
                                <td><input type="text" class="form-control" id="bakifield" value="" name="advance" style="display: none;">
                                </td>
                                <td><input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" required></td>
                                <input type="hidden" class="form-control" value="{{ auth('admin')->user()->name }}" id="admin" name="admin" required>

                                <td><button type="submit" class="btn btn-success" id="btn_submit">AddNew</button></td>
                        </tbody>
                    </table>
                </form>
            </div>
            <div>
                <form action="{{ route('search_ad') }}" method="get">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="start_date">customer contact</label>
                            <input type="number" name="customer" placeholder="Search by customer contact number" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" value="0" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date">End Date</label>
                            <input type="date" value="0" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-3" style="margin-right: 0px;">
                            <div><br></div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search fa-fw"></i> Search
                            </button>

                        </div>

                    </div>
                </form>


            </div>
            <div>

            </div>
            <div class="overflow-mobile">
                <table style="width: 100%!important;">
                    <thead>
                        <tr>
                            <th style="min-width: 100px;">Created At</th>
                            <th style="min-width: 150px;"> Customer </th>
                            <th style="min-width: 100px;">USD</th>
                            <th style="min-width: 100px;">Rate</th>
                            <th style="min-width: 100px;">NRP</th>
                            <th style="min-width: 150px;">Ad Account</th>
                            <th style="min-width: 150px;">Payment Method</th>
                            <th style="min-width: 70px;">Days</th>
                            <th style="min-width: 40px;">Quantity</th>
                            <th style="min-width: 100px;">Status</th>
                            <th style="min-width: 100px;">Baki</th>
                            <th style="min-width: 150px;">Ad Nature/Page</th>
                            <th style="min-width: 90px;">Admin</th>
                            <th>Update</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($ads as $ad)
                        @if($ad->Payment == "Pending")
                        <tr style=" background-color: #26495c; color:white;">
                            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
                                @csrf
                                <td>{{ $ad->created_at->format('Y-m-d')}}</td>
                                <td>
                                    <!-- <div class="form-group">
                                    <select class="form-control" id="customer" name="customer" required>
                                        <option value="">Select Customer</option> -->
                                    <!-- Add options dynamically from your database -->
                                    <!-- Example: -->
                                    <!-- @foreach($customers as $customer)
                                        <option value="{{ $customer->phone }}" {{ $ad->customer == $customer->phone ? 'selected' : '' }}>
                                            {{ $customer->phone }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div> -->
                                    <?php
                                    $customer = Customer::where('phone', $ad->customer)->first();
                                    ?>
                                    {{$customer->display_name}}
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" oninput="calAmt({{$ad->id}})" class="form-control" onchange="calAmt({{$ad->id}})" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" oninput="calAmt({{$ad->id}})" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                                            @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                                            <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}>
                                                {{ $Payment }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                            <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                            <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                            <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                    <!-- <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $ad->Payment }}" id="Payment" name="Payment" required>
                                </div> -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                                            @foreach(['New', 'Extend', 'Both', 'On schedule'] as $status)
                                            <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                                <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                                <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                                <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    @if($ad->advance == '')
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                                        <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                                    </div>
                                    @else
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}">
                                        <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                                    </div>
                                </td>
                                <td>{{ $ad->admin }}
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" value="{{ auth('admin')->user()->name }}" id="admin" name="admin" required>
                                    </div>
                                </td>
                                <td><button type="submit" class="btn btn-primary">Update</button></td>

                            </form>
                            <td>
                                <div class="dropdown" onclick="toggleDropdown_({{$ad->id}})">
                                    <button class="dropdown-button"><i class="fa fa-caret-down"></i></button>
                                    <div style="z-index: 9999; position:relative;" id="{{$ad->id}}" class="dropdown-content">
                                        <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this ad?')"><i class="fa fa-trash"></i></a>
                                        <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="dropdown-item">View</a>
                                        <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="dropdown-item">PDF</a>
                                        <a href="{{URL('/admin/dashboard/send_email/'. $ad->id)}}" class="dropdown-item">email</a>
                                    </div>
                                </div>


                            </td>
                        </tr>
                        @elseif($ad->Payment == "Paused")
                        <tr style=" background-color: #c4a35a;">
                            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
                                @csrf
                                <td>{{ $ad->created_at->format('Y-m-d')}}</td>
                                <td>
                                    <!-- <div class="form-group">
                                    <select class="form-control" id="customer" name="customer" required>
                                        <option value="">Select Customer</option> -->
                                    <!-- Add options dynamically from your database -->
                                    <!-- Example: -->
                                    <!-- @foreach($customers as $customer)
                                        <option value="{{ $customer->phone }}" {{ $ad->customer == $customer->phone ? 'selected' : '' }}>
                                            {{ $customer->phone }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div> -->
                                    <?php
                                    $customer = Customer::where('phone', $ad->customer)->first();
                                    ?>
                                    {{$customer->display_name}}
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" class="form-control" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                                            @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                                            <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}>
                                                {{ $Payment }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                            <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                            <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                            <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                    <!-- <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $ad->Payment }}" id="Payment" name="Payment" required>
                                </div> -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                                            @foreach(['New', 'Extend', 'Both', 'On schedule'] as $status)
                                            <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                            <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                            <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                            <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    @if($ad->advance == '')
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                                        <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                                    </div>
                                    @else
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}">
                                        <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                                    </div>
                                </td>
                                <td>{{ $ad->admin }}
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" value="{{ auth('admin')->user()->name }}" id="admin" name="admin" required>
                                    </div>
                                </td>
                                <td><button type="submit" class="btn btn-primary">Update</button></td>

                            </form>
                            <td>
                                <div class="dropdown" onclick="toggleDropdown_({{$ad->id}})">
                                    <button class="dropdown-button"><i class="fa fa-caret-down"></i></button>
                                    <div style="z-index: 9999; position:relative;" id="{{$ad->id}}" class="dropdown-content">
                                        <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this ad?')"><i class="fa fa-trash"></i></a>
                                        <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="dropdown-item">View</a>
                                        <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="dropdown-item">PDF</a>
                                        <a href="{{URL('/admin/dashboard/send_email/'. $ad->id)}}" class="dropdown-item">email</a>
                                    </div>
                                </div>


                            </td>
                        </tr>
                        @elseif($ad->Payment == "Baki")
                        <tr style=" background-color: #c66b3d; color:white">
                            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
                                @csrf
                                <td>{{ $ad->created_at->format('Y-m-d')}}</td>
                                <td>
                                    <!-- <div class="form-group">
                                    <select class="form-control" id="customer" name="customer" required>
                                        <option value="">Select Customer</option> -->
                                    <!-- Add options dynamically from your database -->
                                    <!-- Example: -->
                                    <!-- @foreach($customers as $customer)
                                        <option value="{{ $customer->phone }}" {{ $ad->customer == $customer->phone ? 'selected' : '' }}>
                                            {{ $customer->phone }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div> -->
                                    <?php
                                    $customer = Customer::where('phone', $ad->customer)->first();
                                    ?>
                                    {{$customer->display_name}}
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" class="form-control" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                                            @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                                            <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}>
                                                {{ $Payment }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                            <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                            <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                            <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                    <!-- <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $ad->Payment }}" id="Payment" name="Payment" required>
                                </div> -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                                            @foreach(['New', 'Extend', 'Both', 'On schedule'] as $status)
                                            <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                            <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                            <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                            <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    @if($ad->advance == '')
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                                        <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                                    </div>
                                    @else
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}">
                                        <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                                    </div>
                                </td>
                                <td>{{ $ad->admin }}
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" value="{{ auth('admin')->user()->name }}" id="admin" name="admin" required>
                                    </div>
                                </td>
                                <td><button type="submit" class="btn btn-primary">Update</button></td>

                            </form>
                            <td>
                                <div class="dropdown" onclick="toggleDropdown_({{$ad->id}})">
                                    <button class="dropdown-button"><i class="fa fa-caret-down"></i></button>
                                    <div style="z-index: 9999; position:relative;" id="{{$ad->id}}" class="dropdown-content">
                                        <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this ad?')"><i class="fa fa-trash"></i></a>
                                        <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="dropdown-item">View</a>
                                        <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="dropdown-item">PDF</a>
                                        <a href="{{URL('/admin/dashboard/send_email/'. $ad->id)}}" class="dropdown-item">email</a>
                                    </div>
                                </div>


                            </td>
                        </tr>
                        @elseif($ad->Payment == "Overpaid")
                        <tr style=" background-color: #e5e5dc;">
                            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
                                @csrf
                                <td>{{ $ad->created_at->format('Y-m-d')}}</td>
                                <td>
                                    <!-- <div class="form-group">
                                    <select class="form-control" id="customer" name="customer" required>
                                        <option value="">Select Customer</option> -->
                                    <!-- Add options dynamically from your database -->
                                    <!-- Example: -->
                                    <!-- @foreach($customers as $customer)
                                        <option value="{{ $customer->phone }}" {{ $ad->customer == $customer->phone ? 'selected' : '' }}>
                                            {{ $customer->phone }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div> -->
                                    <?php
                                    $customer = Customer::where('phone', $ad->customer)->first();
                                    ?>
                                    {{$customer->display_name}}
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" class="form-control" oninput="calAmt({{$ad->id}})" onclick="calAmt({{$ad->id}})" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                                            @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                                            <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}>
                                                {{ $Payment }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                            <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                            <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                            <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                    <!-- <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $ad->Payment }}" id="Payment" name="Payment" required>
                                </div> -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                                            @foreach(['New', 'Extend', 'Both', 'On schedule'] as $status)
                                            <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                            <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                            <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                            <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    @if($ad->advance == '')
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                                        <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                                    </div>
                                    @else
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}">
                                        <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                                    </div>
                                </td>
                                <td>{{ $ad->admin }}
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" value="{{ auth('admin')->user()->name }}" id="admin" name="admin" required>
                                    </div>
                                </td>

                                <td><button type="submit" class="btn btn-primary">Update</button></td>

                            </form>
                            <td>
                                <div class="dropdown" onclick="toggleDropdown_({{$ad->id}})">
                                    <button class="dropdown-button"><i class="fa fa-caret-down"></i></button>
                                    <div style="z-index: 9999; position:relative;" id="{{$ad->id}}" class="dropdown-content">
                                        <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this ad?')"><i class="fa fa-trash"></i></a>
                                        <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="dropdown-item">View</a>
                                        <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="dropdown-item">PDF</a>
                                        <a href="{{URL('/admin/dashboard/send_email/'. $ad->id)}}" class="dropdown-item">email</a>
                                    </div>
                                </div>


                            </td>
                        </tr>
                        @else
                        <tr>
                            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
                                @csrf
                                <td>{{ $ad->created_at->format('Y-m-d')}}</td>
                                <td>
                                    <!-- <div class="form-group">
                                    <select class="form-control" id="customer" name="customer" required>
                                        <option value="">Select Customer</option> -->
                                    <!-- Add options dynamically from your database -->
                                    <!-- Example: -->
                                    <!-- @foreach($customers as $customer)
                                        <option value="{{ $customer->phone }}" {{ $ad->customer == $customer->phone ? 'selected' : '' }}>
                                            {{ $customer->phone }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div> -->
                                    <?php
                                    $customer = Customer::where('phone', $ad->customer)->first();
                                    ?>
                                    {{$customer->display_name}}
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" class="form-control" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                                            @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                                            <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}>
                                                {{ $Payment }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                            <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                            <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                            <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                    <!-- <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $ad->Payment }}" id="Payment" name="Payment" required>
                                </div> -->
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                                            @foreach(['New', 'Extend', 'Both', 'On schedule'] as $status)
                                            <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                            @endforeach
                                            <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                            <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                            <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                            <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    @if($ad->advance == '')
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                                        <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                                    </div>
                                    @else
                                    <div class="form-group" id="{{$ad->id.'bakifield'}}">
                                        <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                                    </div>
                                </td>
                                <td>{{ $ad->admin }}
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" value="{{ auth('admin')->user()->name }}" id="admin" name="admin" required>
                                    </div>
                                </td>
                                <td><button type="submit" class="btn btn-primary">Update</button></td>

                            </form>
                            <td>
                                <div class="dropdown" style="position: relative!important;" onclick="toggleDropdown_({{$ad->id}})">
                                    <button class="dropdown-button" style="position: relative!important;"><i class="fa fa-caret-down"></i></button>
                                    <div id="{{$ad->id}}" class="dropdown-content">
                                        <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this ad?')"><i class="fa fa-trash"></i></a>
                                        <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="dropdown-item">View</a>
                                        <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="dropdown-item">PDF</a>
                                        @if($ad->Payment != "Paid")
                                        <a href="{{URL('/admin/dashboard/send_email/'. $ad->id)}}" class="dropdown-item">email</a>
                                        @endif
                                    </div>
                                </div>


                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $ads->appends(request()->query())->links('pagination::bootstrap-5')}}
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const noteButton = document.getElementById("noteButton");
        const notePopup = document.getElementById("notePopup");
        const closeButton = document.getElementById("closeButton");
        const saveButton = document.getElementById("saveButton");
        const noteInput = document.getElementById("noteInput");
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;


        // Assuming you have a button to trigger the data submission with id="submitButton"
        // const submitButton = document.getElementById('submitButton');

        // submitButton.addEventListener('click', function() {
        // Collect data from the input fields




        noteButton.addEventListener("click", function() {
            // Fetch the note with ID 1 from the server
            fetch("/api/getNote")
                .then(response => response.json())
                .then(data => {
                    // Display the note in the textarea
                    noteInput.value = data.note.note;
                    var id__ = 0;
                    data.datas.forEach(item => {
                        // const rowId = item.id; // Assuming 'id' is the identifier for each row
                        const row = document.getElementById(id__);

                        if (row) {
                            // Find the input fields within the row and update their values
                            row.querySelector('input[name^="customer"]').value = item.customer || '';
                            row.querySelector('input[name^="USD"]').value = item.USD || '';
                            row.querySelector('input[name^="Remarks"]').value = item.Remarks || '';
                            row.querySelector('input[name^="xyz"]').value = item.xyz || '';
                        }
                        id__ = id__ + 1;
                    });
                    // Show the note popup
                    notePopup.style.display = "block";
                })
                .catch(error => {
                    console.error('Error fetching note:', error);
                });
        });

        closeButton.addEventListener("click", function() {
            notePopup.style.display = "none";
        });

        saveButton.addEventListener("click", function() {
            const noteText = noteInput.value;
            const inputRows = document.querySelectorAll('.items');
            const data = [];

            inputRows.forEach(function(row) {
                const rowData = {
                    customer: row.querySelector('input[name^="customer"]').value,
                    USD: row.querySelector('input[name^="USD"]').value,
                    Remarks: row.querySelector('input[name^="Remarks"]').value,
                    xyz: row.querySelector('input[name^="xyz"]').value,
                };

                data.push(rowData);
            });
            // console.log("hello");
            console.log(data);
            // Send the note to the server to save in the database
            fetch("/api/saveNote", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        note: noteText,
                        datas: data
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // Optionally, you can update the UI or provide feedback to the user
                });

            notePopup.style.display = "none";
            noteInput.value = "";
        });
    });
</script>
<script>
    function addRow() {

        // document.getElementById('ad__table').style.display = "block";
        document.getElementById('addnew_btn').style.display = "none";
        document.getElementById('add_new').style.display = "block";
        document.getElementById('btn_submit').style.display = "block";
    }

    function close_() {
        document.getElementById('addnew_btn').style.display = "block";
        document.getElementById('add_new').style.display = "none";
        document.getElementById('btn_submit').style.display = "none";
    }
    //     var newrow =
    //         '<td><input class="form-control" type="number" id="customer" name="customer"></td>' +
    //         '<td><input class="form-control" type="number" id="USD" step="0.01" name="USD"></td>' +
    //         '<td><input class="form-control" type="number" id="Rate" step="0.01" name="Rate"></td>' +
    //         '<td><input class="form-control" type="number" id="NRP" name="NRP"></td>' +
    //         '<td><input class="form-control" type="text" id="Ad_Account" name="Ad_Account"></td>' +
    //         '<td><select class="form-control" id="Payment" name="Payment" required onchange="toggleNewbakiField()">' +
    //         '<option value="Pending">Pending</option>' +
    //         '<option value="Paused">Paused</option>' +
    //         '<option value="FPY Received">FPY Received</option>' +
    //         '<option value="eSewa Received">eSewa Received</option>' +
    //         '<option value="Baki">Baki</option>' +
    //         '</select></td>' +
    //         '<td><input type="number" id="duration" name="Duration" class="form-control" required></td>' +
    //         '<td><input type="number" class="form-control" id="Quantity" name="Quantity" required></td>' +
    //         ' <td><select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">' +
    //         '<option value="New">New</option>' +
    //         '<option value="Extend">Extend</option>' +
    //         '<option value="Both">Both</option>' +
    //         '</select></td>' +
    //         '<td><input type="text" class="form-control" id="bakifield" value="" name="advance" style="display: none;">' +
    //         '</td>' +
    //         '<td><input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" required></td>' +
    //         '<input type="hidden" class="form-control" value="{{ auth("admin")->user()->name }},({{ auth("admin")->user()->id }}) " id="admin" name="admin" required>';

    //     document.getElementById('ad__table').insertAdjacentHTML('afterbegin', newrow);

    // }

    // function smt() {
    //     document.getElementById('submit_table').submit();
    // }
</script>
<script>
    function toggleNewbakiField() {
        var statusSelect = document.getElementById("Payment");
        var advanceField = document.getElementById("bakifield");
        var lists = ["Baki", "Refunded", "Overpaid"];
        if (lists.includes(statusSelect.value)) {
            advanceField.style.display = "block";
        } else {
            advanceField.style.display = "none";
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var usdInput = document.getElementById('USD');
        var rateInput = document.getElementById('Rate');
        var nrpInput = document.getElementById('NRP');

        usdInput.addEventListener('input', calculateNRP);
        rateInput.addEventListener('input', calculateNRP);

        function calculateNRP() {
            var usd = parseFloat(usdInput.value) || 0;
            var rate = parseFloat(rateInput.value) || 0;
            var nrp = usd * rate;
            nrpInput.value = nrp.toFixed(2);
        }
    });
</script>
<script>
    function calAmt(adId) {
        console.log("hello");
        var usdInput = document.getElementById(adId + 'USD');
        var rateInput = document.getElementById(adId + 'Rate');
        var nrpInput = document.getElementById(adId + 'NRP');

        usdInput.addEventListener('input', calculateNRP);
        rateInput.addEventListener('input', calculateNRP);
        usdInput.addEventListener('change', calculateNRP);
        rateInput.addEventListener('change', calculateNRP);

        function calculateNRP() {
            var usd = parseFloat(usdInput.value) || 0;
            var rate = parseFloat(rateInput.value) || 0;
            var nrp = usd * rate;
            nrpInput.value = nrp.toFixed(2);
        }
    }
</script>
<script>
    function togglebakiField(adId) {
        var statusSelect = document.getElementById(adId);
        var advanceField = document.getElementById(adId + 'field');
        var lists = ["Baki", "Refunded", "Overpaid"];
        if (lists.includes(statusSelect.value)) {
            advanceField.style.display = "block";
        } else {
            advanceField.style.display = "none";
        }
    }
</script>
<script>
    function toggleDropdown_(adId) {
        var dropdown = document.getElementById(adId);
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        dropdown.style.zIndex = 9999;
        dropdown.style.position = "absolute";
    }
</script>
<script>
    // Function to toggle the dropdown
    function toggleDropdown() {
        var dropdown = document.getElementById("myDropdown");
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-btn')) {
            var dropdowns = document.getElementsByClassName("dropdown-menu");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === "block") {
                    openDropdown.style.display = "none";
                }
            }
        }
    }



    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-button')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === "block") {
                    openDropdown.style.display = "none";
                }
            }
        }
    }
</script>
<script>

</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection