@extends('layouts.master')

@section('content')

<div class="d-flex">

    @include('components.sidebar-dosen')

    <div class="main flex-fill">
        @yield('page-content')
    </div>

</div>

@endsection
