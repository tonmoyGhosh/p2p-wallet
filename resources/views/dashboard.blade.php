@extends('master.app')

@section('content')

<div class="container">
    
    <br><br><br>
    <button type="button" class="btn" id="sendMoney">Send Money</button>
    <button type="button" class="btn btn-default" id="statsReport">Stats Report</button>
    <button type="button" class="btn btn-primary" id="logout">Logout</button>
    <h1>Dashboard</h1>

</div>

@endsection

@push('scripts')

<script>

    if(!window.localStorage.getItem('apiToken'))
    {   
        window.location = "{{ route('login') }}";
    }

    $('#sendMoney').click(function (e)
    { 
        window.location = "{{ route('send-money') }}";
    });

    $('#statsReport').click(function (e)
    { 
        window.location = "{{ route('stats-report') }}";
    });

    // logout user
    $('#logout').click(function (e)
    {       
        // get api token from local storage
        apiToken = window.localStorage.getItem('apiToken');
        
        // set api token in headers
        $.ajaxSetup({
            headers: {
                'Authorization': 'Bearer ' + apiToken
            }
        });

        $.ajax({
            url: "{{ url('api/v1/logout') }}",
            type: "GET",
            dataType: 'json',
            success: function (data)
            {   
                console.log(data);

                if(data.success == false)
                {
                    if(data.message)
                    {
                        alert(data.message);
                        window.location = "{{ route('dashboard') }}";
                    }
                        
                }
                if(data.success == true)
                {   
                    // remove api token from local storage
                    window.localStorage.removeItem('apiToken');
                    window.location = "{{ route('login') }}";
                }
                
            },
            error: function (err)
            {
                alert('Something went wrong, try again');
            }
        });
    });

</script>

@endpush