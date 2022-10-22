@extends('master.app')

@section('content')

<div class="container">
  
  <br><br><br><br>

  <form class="form-horizontal" id="formDiv" name="formDiv">
    
    <div class="form-group">
      <label class="control-label col-sm-2" for="email">Email:</label>
      <div class="col-sm-6">
        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
        <p id="emailMsg" style="color: red"></p>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2" for="password">Password:</label>
      <div class="col-sm-6">          
        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
        <p id="passwordMsg" style="color: red"></p>
      </div>
    </div>

    <div class="form-group">        
        <div class="col-sm-offset-2 col-sm-10">
            <p id="invalidMsg" style="color: red"></p>
            <button type="button" class="btn btn-default" id="login">Login</button>
        </div>
    </div>

  </form>

</div>

@endsection

@push('scripts')

<script>

    if(window.localStorage.getItem('apiToken'))
    {
        window.location = "{{ route('dashboard') }}";
    }

    $('#login').click(function (e)
    {   
        $.ajax({
            data: $('#formDiv').serialize(),
            url: "{{ url('api/v1/login') }}",
            type: "POST",
            dataType: 'json',
            success: function (data)
            {   
                $('#emailMsg').html('');
                $('#passwordMsg').html('');
                $('#invalidMsg').html('');
            
                if(data.errors)
                {   
                    if(data.errors.email)
                        $('#emailMsg').html(data.errors.email[0]);
                    if(data.errors.password)
                        $('#passwordMsg').html(data.errors.password[0]);
                }
                if(data.success == false)
                {
                    if(data.message)
                        $('#invalidMsg').html(data.message);
                }
                if(data.success == true)
                {   
                    // set api token in local storage
                    window.localStorage.setItem('apiToken', data.apiToken);
                    window.location = "{{ route('dashboard') }}";
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