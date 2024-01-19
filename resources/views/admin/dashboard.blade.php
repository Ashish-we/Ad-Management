<?php

use App\Models\UserPrivilege;

$is_super_admin = UserPrivilege::select('full_or_partial')->where('user_id', auth('admin')->user()->id)->first();
if (!$is_super_admin['full_or_partial']) {
    $userPrivileges = UserPrivilege::select('option')->where('user_id', auth('admin')->user()->id)->first();
    $userPrivileges = explode(',', $userPrivileges['option']);
} else {
    $userPrivileges = [1, 2, 3, 4, 5, 6, 7];
}
// {{ in_array(6, $userPrivileges) ? 'checked' : '' }}
?>

@extends('admin.layout.layout')

@section('content')
<style>
    .table-container {
        display: flex;
        flex-wrap: wrap;
        /* Allow tables to wrap to the next line */
    }

    .table-responsive {
        width: 100%;
        /* Occupy full width on smaller screens */
        margin-bottom: 20px;
        /* Add some margin between tables */
    }

    table {
        border-collapse: collapse;
        width: 100%;
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

    h3 {
        margin-bottom: 10px;
    }
</style>
<div style="display:  {{ in_array(1, $userPrivileges) ? 'block' : 'none' }}">
    <section class="content">
        <div class=" container-fluid">
            <!-- Small boxes (Stat box) -->
            @if($is_super_admin['full_or_partial'])
            <div class="row">
                <div class="ml-5 mr-5 col-lg-5 col-6 shadow-sm p-3 mb-5 bg-white rounded">
                    <!-- small box -->
                    <div class="table-responsive">
                        <h6>This Month AD Income summary</h6>
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Total USD</th>
                                    <th>Total NRP</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>{{$startOfMonth->format('Y-m')}}</td>
                                    <td>$ {{ $monthlyAdIncomeSummaries->totalUSD }}</td>
                                    <td>Rs {{ $monthlyAdIncomeSummaries->totalNRP }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h6>This Month Expences summary</h6>
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Total USD</th>
                                    <th>Total NRP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$startOfMonth->format('Y-m')}}</td>
                                    <td>$ {{ $monthlyAdIncomeSummaries->totalUSD }}</td>
                                    @if($monthlyClientSummaries->totalUSD != 0)
                                    <td>Rs {{ ($monthlyClientSummaries->totalNRP) + $monthlyExp->totalAmt }}</td>
                                    <!-- <td>Rs {{ (($monthlyClientSummaries->totalNRP/ $monthlyClientSummaries->totalUSD)*$monthlyAdIncomeSummaries->totalUSD) + $monthlyExp->totalAmt }}</td> -->
                                    @else
                                    <td>none</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h6>This Month Savings summary</h6>
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <!-- <th>Total USD</th> -->
                                    <th>Total NRP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$startOfMonth->format('Y-m')}}</td>
                                    <!-- <td>$ {{$monthlyClientSummaries->totalUSD - $monthlyAdIncomeSummaries->totalUSD}}</td> -->
                                    @if($monthlyClientSummaries->totalUSD != 0)
                                    <td>Rs {{ ($monthlyAdIncomeSummaries->totalNRP) - (($monthlyClientSummaries->totalNRP/ $monthlyClientSummaries->totalUSD)*$monthlyAdIncomeSummaries->totalUSD) - $monthlyExp->totalAmt }}</td>
                                    @else
                                    <td>none</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h6>This Month To be Loaded USD(-ve => overloaded)</h6>
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Total USD</th>
                                    <th>Total NRP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$startOfMonth->format('Y-m')}}</td>
                                    <td>$ {{$monthlyAdIncomeSummaries->totalUSD - $monthlyClientSummaries->totalUSD }}</td>
                                    @if($monthlyClientSummaries->totalUSD != 0)
                                    <td>Rs {{($monthlyClientSummaries->totalNRP/$monthlyClientSummaries->totalUSD)*($monthlyAdIncomeSummaries->totalUSD - $monthlyClientSummaries->totalUSD)}}</td>
                                    @else
                                    <td>{{$monthlyAdIncomeSummaries->totalNRP}}</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- ./col -->
                <div class="ml-5 col-lg-5 col-6 shadow-sm p-3 mb-5 bg-white rounded">
                    <div class="table-container mt-5">
                        <div class="table-responsive">
                            <!-- <h6>Total summary</h6> -->
                            <table>
                                <thead>
                                    <tr>
                                        <th>Total USD IN Cards</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>$ {{ $Cardsummary->totalUSD }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="table-container mt-5">
                        <div class="table-responsive">
                            <h6>This Month Credit summary</h6>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Total USD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyCreditSummaries as $summary)
                                    <tr>
                                        <td>{{$startOfMonth->format('Y-m')}}</td>
                                        <td>$ {{ $summary->totalUSD }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-container mt-5">
                        <div class="table-responsive">
                            <h6>This Month Debit summary</h6>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Total USD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyDebitSummaries as $summary)
                                    <tr>
                                        <td>{{$startOfMonth->format('Y-m')}}</td>
                                        <td>$ {{ $summary->totalUSD }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- ./col -->

                <!-- ./col -->

                <!-- ./col -->
            </div>
            @endif
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section style="">
                    @if( in_array(3, $userPrivileges))
                    <div class="container mt-4" style="display: {{ in_array(3, $userPrivileges) ? 'block' : 'none' }}">
                        <div class="container mt-5">
                            <div class="card">
                                <div class="card-header">
                                    <h6>ADD Customer</h6>
                                </div>
                                <div class="card-body">
                                    <table>
                                        <form method="post" action="{{ url('/admin/dashboard/customer/add') }}">
                                            @csrf
                                            <thead>
                                                <th>Name</th>
                                                <th>Display Name</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Phone</th>
                                                <th>Action</th>
                                            </thead>
                                            <td>
                                                <div class="mb-3">
                                                    <!-- <label for="name" class="form-label">Name</label> -->
                                                    <input type="text" class="form-control" id="name" name="name" required>
                                                    @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mb-3">
                                                    <input type="text" class="form-control" id="display_name" name="display_name">
                                                </div>
                                                @error('display_name')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            <td>
                                                <div class="mb-3">
                                                    <!-- <label for="email" class="form-label">Email</label> -->
                                                    <input type="email" class="form-control" id="email" name="email" required>
                                                    @error('email')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>

                                            <td>
                                                <div class="mb-3">
                                                    <!-- <label for="address" class="form-label">Address</label> -->
                                                    <input type="text" class="form-control" id="address" name="address" required>
                                                    @error('address')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>

                                            <td>
                                                <div class="mb-3">
                                                    <!-- <label for="phone" class="form-label">Phone</label> -->
                                                    <input type="text" class="form-control" id="phone" name="phone" required>
                                                    @error('phone')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td><button type="submit" class="btn btn-primary">Add</button></td>

                                        </form>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- /.card -->


                </section>
                <section>
                    @if( in_array(4, $userPrivileges))
                    <div class="container mt-4">
                        <div class="container mt-5" style="display: {{ in_array(4, $userPrivileges) ? 'block' : 'none' }}">
                            <div class="card">
                                <div class="card-header">
                                    <h6>ADD Client</h6>
                                </div>
                                <div class="card-body">
                                    <table>
                                        <form method="post" action="{{ url('/admin/dashboard/client/add') }}">
                                            @csrf
                                            <thead>
                                                <th>Name</th>
                                                <th>USD</th>
                                                <th>Rate</th>
                                                <th>NRP</th>
                                                <th>Account</th>
                                                <th>Action</th>
                                            </thead>
                                            <td>
                                                <div class="mb-3">
                                                    <!-- <label for="name" class="form-label">Name</label> -->
                                                    <input type="text" class="form-control" id="name" name="name" required>
                                                    @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <!-- <label for="USD">USD:</label> -->
                                                    <input type="number" step="0.01" class="form-control" id="USD" name="USD" required>
                                                    @error('USD')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <!-- <label for="Rate">Rate:</label> -->
                                                    <input type="number" step="0.01" class="form-control" id="Rate" name="Rate" required>
                                                    @error('Rate')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <!-- <label for="NRP">NRP:</label> -->
                                                    <input type="number" step="0.01" class="form-control" id="NRP" name="NRP" required>
                                                    @error('NRP')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <!-- <label for="Ad_Account">Account:</label> -->
                                                    <input class="form-control" id="Ad_Account" name="account" required>
                                                    @error('account')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-primary">Add</button>
                                            </td>
                                        </form>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    @endif
                </section>
                <!-- /.Left col -->

            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
</div>
@endsection