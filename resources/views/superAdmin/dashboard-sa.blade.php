@extends('layouts.parent-layout')

@section('content')
    <div class="row">
        <div class="col-md-12 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h1 class="text-white">Dashboard Super Admin</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
