<div class="modal fade" id="userEditModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="userEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="userEditModalLabel">Edit user</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('caronte.management.users.update') }}" id="userEditForm">
                    @csrf
                    <input type="hidden" name="uri_user" id="editUserUri" value="">
                    <div class="mb-3">
                        <label for="editNameInput" class="form-label fw-semibold">Name</label>
                        <input name="name" type="text" class="form-control" id="editNameInput" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmailInput" class="form-label fw-semibold">Email</label>
                        <input name="email" type="email" class="form-control" id="editEmailInput" disabled>
                    </div>
                    @include('caronte::management.users.partials.roles-checkboxes', ['idPrefix' => 'edit'])

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-2"></i>Save changes
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
