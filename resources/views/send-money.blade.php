@extends('master.app')

@section('content')

<div class="container">

  @include('master/menu')

  <br><br>

  <form class="form-horizontal" id="formDiv" name="formDiv">
    
    <h5 id="wallet_balance"></h5>
    <h5 id="currency"></h5>

    <div class="form-group">
      <label class="control-label col-sm-2">User:</label>
      <div class="col-sm-6">
        <select class="form-control" name="receive_user_id" id="receive_user_id">
            <option value="">Select User</option>
        </select>
        <p id="receive_user_id_msg" style="color: red"></p>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2">Amount:</label>
      <div class="col-sm-6">          
        <input type="number" class="form-control" id="amount" placeholder="Enter Amount" name="amount" min="1">
        <p id="amount_msg" style="color: red"></p>
      </div>
    </div>

    <div class="form-group">        
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" class="btn btn-default" id="submit">Submit</button>
        </div>
    </div>

  </form>

</div>

@endsection

@push('scripts')

<script>

    console.log(window.localStorage.getItem('apiToken'));

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
            url: "{{ url('api/v1/getLoginUserInfo') }}",
            type: "GET",
            dataType: 'json',
            success: function (data)
            {   
                if(data.success == true)
                {
                    $('#wallet_balance').html('Your Wallet Balance:' + data.userInfo.wallet.amount);
                    $('#currency').html('Your Currency:' + data.userInfo.wallet.currency);
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

        // generate user list
        $.ajax({
            url: "{{ url('api/v1/getUserList') }}",
            type: "GET",
            dataType: 'json',
            success: function (data)
            {   
                if(data.success == true)
                {
                    $('#receive_user_id').html('');
                    var addOption = `<option value="">Select User</option>`;

                    $.each(data.userList, function( key, value ) 
                    {
                        addOption += `<option value="${value.id}"> ${value.name} ( ${value.email} ) - ${value.wallet.currency} </option>`;
                    });

                    $('#receive_user_id').append(addOption);
                } 
            },
            error: function (err){}
        });

    });

    $('#submit').click(function (e)
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
            data: $('#formDiv').serialize(),
            url: "{{ url('api/v1/sendMoney') }}",
            type: "POST",
            dataType: 'json',
            success: function (data)
            {   
                $('#receive_user_id_msg').html('');
                $('#amount_msg').html('');
            
                if(data.errors)
                {   
                    if(data.errors.receive_user_id)
                        $('#receive_user_id_msg').html(data.errors.receive_user_id[0]);
                    if(data.errors.amount)
                        $('#amount_msg').html(data.errors.amount[0]);
                }
                if(data.success == true)
                {   
                    
                    //    window.location = "{{ route('dashboard') }}";
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