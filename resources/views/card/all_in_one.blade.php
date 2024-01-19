@extends('admin.layout.layout')

@section('content')
<style>
    .table-container {
        display: flex;
        gap: 20px;
        width: 100%;
    }


    .table-headings {
        display: flex;
        gap: 20px;
        width: 100%;
    }

    .div-container-1 {
        width: 25%;
    }

    .div-container-2 {
        width: 37.5%;
        display: flex;
        align-items: flex-start;
        border: 1px solid #dee2e6;
        overflow-x: auto;

    }

    .div-container-3 {
        width: 37.5%;
        display: flex;
        align-items: flex-start;
        border: 1px solid #dee2e6;
        overflow-x: auto;
    }

    .box2,
    .box3 {
        width: 300px !important;
        border-right: 1px solid #dee2e6;

    }

    .box2:last-child,
    .box3:last-child {
        border-right: none;
    }

    .table td {
        padding: 10px !important;
    }
</style>
<style>
    .input-group {
        display: flex;
        align-items: center;
    }

    #card_number {
        padding-right: 30px;
        /* Space for the icon */
    }

    .btn-icon {
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        /* background-color: #007bff; */
        /* color: #fff; */
        cursor: pointer;
        border: none;
        padding: 5px 10px;
    }
</style>
<div>
    <!-- <div class="table-headings">
        <div class="head-div">

        </div>
        <div class="head-div1">
            <h2>Credit</h2>
        </div>
        <div class="head-div2">
            <h2>Debit</h2>
        </div>
    </div> -->
    <div class="container ml-0 mt-0 mb-0" style="width: 500px;">
        <div class="card">
            <div class="card-body">
                <h4>Total Amount in cards : ${{ $summary->totalUSD }}</h4>
            </div>
        </div>
    </div>
    <div style="width: 80%; margin-left:10%;" class="alert">
        @if (session('status'))
        <div class="alert alert-warning" role="alert">
            {{ session('status') }}
        </div>
        @endif
    </div>
    <div style="display: flex;">
        <div style="margin-left: 10px;">
            <h1>Card Details</h1>
        </div>
        <div style="margin-left: 33.33%;">
            <h1>credit</h1>
        </div>
        <div style="margin-left: 33.33%;">
            <h1>Debit</h1>
        </div>
    </div>
    <div class="table-container">
        <div class="div-container-1">
            <table class="box1 table">
                <thead>
                    <th>Name</th>
                    <th>Account</th>
                    <th>Balance</th>
                </thead>
                @foreach($cards as $card)
                <tr>
                    <td>{{$card->name}}</td>
                    <td>{{$card->card_number}}</td>
                    <td>$ {{$card->USD}}</td>
                </tr>
                @endforeach

                <form method="post" action="{{ url('/admin/dashboard/card/add') }}">
                    @csrf
                    <tr>
                        <td>
                            <div class="mb-3">
                                <!-- <label for="name" class="form-label">Name</label> -->
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Name" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <div class="mb-3">
                                <!-- <label for="card_number" class="form-label">Card Number</label> -->
                                <input type="text" class="form-control @error('card_number') is-invalid @enderror" id="card_number" name="card_number" value="{{ old('card_number') }}" placeholder="Account Number" required>
                                @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <div class="mb-3">
                                <!-- <label for="USD" class="form-label">USD</label> -->
                                <input type="number" step="0.01" class="form-control @error('USD') is-invalid @enderror" id="USD" name="USD" value="{{ old('USD') }}" placeholder="Balance" required>
                                @error('USD')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><button style="width: 100%;" type="submit" class="btn btn-primary">Add Card</button></td>
                    </tr>
                </form>

            </table>
        </div>

        <div class="div-container-2">
            <!-- <h1>Credit details</h1> -->
            @foreach($cards as $card)
            <table class="box2 table">

                <thead>
                    <th style="min-width: 100px;">{{$card->name}}</th>
                </thead>
                <tbody>
                    @foreach($credits as $credit)
                    <tr>
                        @if(@$credit->card_id == $card->id)
                        <td>$ {{$credit->USD}}</td>
                        @endif
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            <form method="post" action="{{ url('/admin/dashboard/credit/add') }}">
                                @csrf
                                <input type="number" step="0.01" class="form-control @error('USD') is-invalid @enderror" id="USD" name="USD" value="{{ old('USD') }}" required>
                                <div class="input-group">
                                    <input type="hidden" id="card_number" name="card_number" value="{{$card->card_number}}">
                                    <button style="display: none;" type="submit" class="btn-icon"><i class="fa fa-plus"></i></button>
                                </div>
                            </form>
                        </td>
                    </tr>
                </tbody>

            </table>
            @endforeach
        </div>
        <div class="div-container-3">
            @foreach($cards as $card)
            <table class="box3 table">

                <thead>
                    <th style="min-width: 100px;">{{$card->name}}</th>
                </thead>
                @foreach($debits as $debit)

                <tr>
                    @if(@$debit->card_id == $card->id)
                    <td>$ {{$debit->USD}}</td>
                    @endif
                </tr>

                @endforeach
                <tr>
                    <td>
                        <form method="post" action="{{ url('/admin/dashboard/debit/add') }}">
                            @csrf
                            <input type="number" step="0.01" class="form-control @error('USD') is-invalid @enderror" id="USD" name="USD" value="{{ old('USD') }}" required>
                            <div class="input-group">
                                <input type="hidden" id="card_number" name="card_number" value="{{$card->card_number}}">
                                <button type="submit" style="display: none;" class="btn-icon"><i class="fa fa-plus"></i></button>
                            </div>
                        </form>
                    </td>
                </tr>
            </table>
            @endforeach

        </div>
    </div>


</div>

@endsection