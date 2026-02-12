@extends('caronte::layouts.base')

@section('content')
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="font-weight-light my-2">@yield('title-form')</h3>
                    </div>

                    <div class="card-body p-5">
                        @yield('form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
