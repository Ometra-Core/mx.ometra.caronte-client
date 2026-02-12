<div class="tab-pane fade" id="management-content" role="tabpanel" aria-labelledby="management-tab">

    <div class="card-header text-center py-3" style="background-color: #d1dcd0;">
        <h5 class="mb-0 fw-normal fs-5">Attach role to user</h5>
    </div>

    <div class="card-body p-4">
        <div class="mb-3">
            <label for="searchUser" class="form-label fw-semibold">Search user:</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="switchCheckDefault">
                <label class="form-check-label" for="switchCheckDefault">Incluir usuarios ajenos a la aplicación</label>
            </div>
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
                <tbody id="usersListContainer">

                    @foreach ($users as $user)
                        <tr>
                            <td class="border-end text-start ps-3">{{ $user['name'] }}</td>
                            <td class="border-end text-start ps-3">{{ $user['email'] }}</td>
                            <td>
                                <button class="btn btn-sm btn-link text-dark" title="Settings"
                                    data-userId="{{ $user['uri_user'] }}" data-userName="{{ $user['name'] }}"
                                    data-bs-toggle="modal" data-bs-target="#rolesModal">
                                    <i class="fa-solid fa-gear fs-5"></i>
                                </button>

                                <button class="btn btn-sm btn-link text-dark" title="Edit" data-bs-toggle="modal"
                                    data-userId="{{ $user['uri_user'] }}" data-userName="{{ $user['name'] }}"
                                    data-user-email={{ $user['email'] }} data-bs-target="#managesModal">
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
@include('caronte::management.modals.user-roles-list')
@include('caronte::management.modals.user-edit')
@include('caronte::management.modals.user-assign-role')
@include('caronte::management.modals.user-detach-role')
@include('caronte::management.modals.user-delete')
