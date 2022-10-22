@extends('master.app')

@section('content')

<div class="container">
    
    @include('master/menu')

    <h1>Dashboard</h1>

</div>

@endsection

@push('scripts')

<script>

    if(!window.localStorage.getItem('apiToken'))
    {   
        window.location = "{{ route('login') }}";
    }

</script>

@endpush