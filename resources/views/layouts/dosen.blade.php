@extends('layouts.master')

@section('content')

<div class="d-flex" style="height: 100vh;">

    @include('components.sidebar-dosen')

    <div class="main flex-fill" style="min-width: 0;">
        @yield('page-content')
    </div>

</div>

@endsection
