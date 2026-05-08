@extends('layouts.master')

@section('content')

<div class="d-flex" style="height: 100vh;">

    @include('components.sidebar-mahasiswa')

    <div class="main flex-fill">
        @yield('page-content')
    </div>

</div>

@endsection
