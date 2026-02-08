@extends('caronte::layouts.base')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-lg-6">

                <ul class="nav nav-pills nav-fill custom-tabs-container p-2 rounded-top" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-tab-link active w-100" id="users-tab" data-bs-toggle="pill"
                            data-bs-target="#users-content" type="button" role="tab" aria-controls="users-content"
                            aria-selected="true">Users</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-tab-link w-100" id="roles-tab" data-bs-toggle="pill"
                            data-bs-target="#roles-content" type="button" role="tab"
                            aria-controls="roles-content" aria-selected="false">Roles</button>
                    </li>
                </ul>

                <div class="card custom-card border-0 shadow-sm">

                    <div class="tab-content" id="myTabContent">
                        @include('caronte::management.users.list-tab')
                        @include('caronte::management.roles.list-tab')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
