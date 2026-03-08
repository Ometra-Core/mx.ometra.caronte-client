@extends('caronte::base')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-lg-6">

                <ul class="nav nav-pills nav-fill custom-tabs-container p-2 rounded-top" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-tab-link active w-100" id="create-tab" data-bs-toggle="pill"
                            data-bs-target="#create-content" type="button" role="tab" aria-controls="create-content"
                            aria-selected="true">Create</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-tab-link w-100" id="management-tab" data-bs-toggle="pill"
                            data-bs-target="#management-content" type="button" role="tab"
                            aria-controls="management-content" aria-selected="false">Management</button>
                    </li>
                </ul>

                <div class="card custom-card border-0 shadow-sm">

                    <div class="tab-content" id="myTabContent">
                        @include('caronte::Management.Create')
                        @include('caronte::Management.Management')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
