<div class="tab-pane fade" id="roles-content" role="tabpanel" aria-labelledby="roles-tab">

    <div class="card-header text-center py-3" style="background-color: #d1dcd0;">
        <h5 class="mb-0 fw-normal fs-5">Role Management</h5>
    </div>

    <div class="card-body p-4">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-semibold">Registered Roles</h6>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleCreateModal">
                <i class="fa-solid fa-plus me-2"></i>Create New Role
            </button>
        </div>

        <div class="table-responsive border rounded">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="border-end" style="width: 25%;">Role Name</th>
                        <th scope="col" class="border-end" style="width: 50%;">Description</th>
                        <th scope="col" class="text-center" style="width: 25%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="rolesListContainer">
                    @if(isset($roles) && count($roles) > 0)
                        @foreach ($roles as $role)
                            <tr>
                                <td class="border-end ps-3">
                                    <strong>{{ $role['name'] ?? 'N/A' }}</strong>
                                </td>
                                <td class="border-end ps-3">
                                    {{ $role['description'] ?? 'No description' }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-link text-dark" title="Edit role"
                                        data-role-uri="{{ $role['uri_applicationRole'] }}" 
                                        data-role-name="{{ $role['name'] }}"
                                        data-role-description="{{ $role['description'] ?? '' }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#roleEditModal">
                                        <i class="fa-solid fa-pen-to-square fs-5"></i>
                                    </button>

                                    <button class="btn btn-sm btn-link text-danger" title="Delete role"
                                        data-role-uri="{{ $role['uri_applicationRole'] }}" 
                                        data-role-name="{{ $role['name'] }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#roleDeleteModal">
                                        <i class="fa-solid fa-trash fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="fa-solid fa-circle-info me-2"></i>No roles registered yet.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('caronte::management.roles.modals.create')
@include('caronte::management.roles.modals.edit')
@include('caronte::management.roles.modals.delete')
