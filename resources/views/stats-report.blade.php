@extends('master.app')

@section('content')

<div class="container">
    
    @include('master/menu')

    <h3>Most Conversion Report</h3>
    <div class="card" style="width: 25rem;">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><b>User Used Most Conversion</b></li>
            <li class="list-group-item" id="mostConvert"></li>
        </ul>
    </div>

    <br>

    <h3>Total Amount Report (User Wise)</h3>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Total Amount (Converted)</th>
            </tr>
        </thead>
        <tbody id="userTotalAmount"></tbody>
    </table>

    <br>

    <h3>Third Highest Amount Report (User Wise)</h3>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Amount (3rd Highest)</th>
            </tr>
        </thead>
        <tbody id="userThirdHighestAmount"></tbody>
    </table>

</div>

@endsection

@push('scripts')

<script>

    if(!window.localStorage.getItem('apiToken'))
    {   
        window.location = "{{ route('login') }}";
    }

    $(document).ready(function() 
    {   
        // get api token from local storage
        apiToken = window.localStorage.getItem('apiToken');
        
        // set api token in headers
        $.ajaxSetup({
            headers: {
                'Authorization': 'Bearer ' + apiToken
            }
        });

        // get login user info
        $.ajax({
            url: "{{ url('api/v1/statsReport') }}",
            type: "GET",
            dataType: 'json',
            success: function (data)
            {       
                $('#mostConvert').html('');
                $('#userTotalAmount').html('');

                if(data.success == true)
                {   
                    // generate most conversion report
                    $('#mostConvert').html(data.mostUsedConversion.name + ' - ' + data.mostUsedConversion.email);

                    // generate user wise convert total amount report
                    $.each(data.userTotalCovertAmount, function( key, value ) 
                    {   
                        addTableData = `<tr>`;
                        addTableData += `<td> ${value.name} </td> <td> ${value.email} </td> <td> ${value.total_convert_amount} </td>`;
                        addTableData += `</tr>`;

                        $('#userTotalAmount').append(addTableData);
                    });

                    // generate user wise 3rd highest amount report
                    $.each(data.userThirdHighestAmount, function( key, value ) 
                    {   
                        if(value.third_highest_amount)
                        {
                            addTableData = `<tr>`;
                            addTableData += `<td> ${value.user_name} </td> <td> ${value.user_email} </td> <td> ${value.third_highest_amount} </td>`;
                            addTableData += `</tr>`;

                            $('#userThirdHighestAmount').append(addTableData);
                        }

                    });

                }

            },
            error: function (err)
            {   
                if(err.status == 401)
                {    
                    alert('Your session is time out, logout now & log in again');
                }
                else
                {
                    alert('Something went wrong, try again');
                }
            }
        });

    });

</script>

@endpush