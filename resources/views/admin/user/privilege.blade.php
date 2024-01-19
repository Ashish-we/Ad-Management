@extends('admin.layout.layout')

@section('content')

<div class="container">
    <h1>User Privileges</h1>
    <form id="privilegeForm">
        @csrf
        <div class="form-group">
            <label for="privileges" style="font-size: xx-large;">Select Privileges:</label>
            <div class="row mt-2" style="font-size: x-large;">
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        <input style="width:40px; height:40px;" type="checkbox" class="custom-control-input" id="dashboard" name="privileges[0]" value="1" {{ in_array(1, $userPrivileges) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="dashboard">Dashboard</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2" style="font-size: x-large;">
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="ads" name="privileges[1]" value="2" {{ in_array(2, $userPrivileges) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="ads">Ads</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2" style="font-size: x-large;">
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customers" name="privileges[2]" value="3" {{ in_array(3, $userPrivileges) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customers">Customers</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2" style="font-size: x-large;">
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="client" name="privileges[2]" value="4" {{ in_array(4, $userPrivileges) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="client">Client</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2" style="font-size: x-large;">
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="item" name="privileges[2]" value="5" {{ in_array(5, $userPrivileges) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="item">Item</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2" style="font-size: x-large;">
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="invoice" name="privileges[2]" value="6" {{ in_array(6, $userPrivileges) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="invoice">Invoice</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2" style="font-size: x-large;">
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="card" name="privileges[2]" value="7" {{ in_array(7, $userPrivileges) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="card">Card(includes credit and debit)</label>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" onclick="submitForm({{$user->id}})" class="btn btn-primary">Save Privileges</button>
    </form>
</div>

<script>
    function submitForm(user_id) {
        let selectedPrivileges = [];
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        document.querySelectorAll('input[name^="privileges"]:checked').forEach(function(checkbox) {
            selectedPrivileges.push(checkbox.value);
        });

        let privilegesString = selectedPrivileges.join(',');
        console.log(privilegesString);


        fetch('/admin/dashboard/user/privilege/' + user_id, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    privileges: privilegesString
                }),
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                alert('Privileges updated successfully');

            });

    }
</script>
@endsection