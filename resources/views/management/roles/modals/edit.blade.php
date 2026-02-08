<div class="modal fade" id="roleEditModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="roleEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="roleEditModalLabel">Edit role</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('caronte.management.roles.update') }}" id="roleEditForm">
                    @csrf
                    <input type="hidden" name="uri_role" id="editRoleUri" value="">
                    
                    <div class="mb-3">
                        <label for="roleNameDisplay" class="form-label fw-semibold">Role Name:</label>
                        <input type="text" class="form-control" id="roleNameDisplay" disabled>
                        <small class="form-text text-muted">Role names cannot be changed</small>
                    </div>
                    <div class="mb-3">
                        <label for="roleDescriptionUpdate" class="form-label fw-semibold">Description:</label>
                        <textarea name="description" class="form-control" id="roleDescriptionUpdate" rows="3"></textarea>
                        <small class="form-text text-muted">Update the role description</small>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
