<div class="tab-pane fade" id="management-content" role="tabpanel" aria-labelledby="management-tab">

    <div class="card-header text-center py-3" style="background-color: #d1dcd0;">
        <h5 class="mb-0 fw-normal fs-5">Attach role to user</h5>
    </div>

    <div class="card-body p-4">
        <div class="mb-3">
            <label for="searchUser" class="form-label fw-semibold">Search user:</label>

            <div class="input-group">
                <input type="text" class="form-control" id="searchUser" placeholder="Type name or email...">

                <button class="btn btn-outline-secondary" type="button" id="button-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive border rounded">
            <table class="table table-hover mb-0 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="border-end">Name</th>
                        <th scope="col" class="border-end">Email</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="border-end text-start ps-3">{{ $user['name'] }}</td>
                            <td class="border-end text-start ps-3">{{ $user['email'] }}</td>
                            <td>
                                <button class="btn btn-sm btn-link text-dark" title="Settings" data-bs-toggle="modal"
                                    data-bs-target="#rolesModal">
                                    <i class="fa-solid fa-gear fs-5"></i>
                                </button>

                                <button class="btn btn-sm btn-link text-dark" title="Edit" data-bs-toggle="modal"
                                    data-bs-target="#managesModal">
                                    <i class="fa-solid fa-pen-to-square fs-5"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@include('caronte::Management.Modals.RolesModal')
@include('caronte::Management.Modals.ManagesModal')
