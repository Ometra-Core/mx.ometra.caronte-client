<div class="modal fade" id="roleDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="roleDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h1 class="modal-title fs-5" id="roleDeleteModalLabel">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>Confirm Role Deletion
                </h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete the role <strong id="roleNameDelete"></strong>?</p>
                <p class="text-muted small">All users with this role will lose it. If this is the last role assigned to any user, they may lose access to the application.</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('caronte.management.roles.delete') }}" id="roleDeleteForm">
                    @csrf
                    <input type="hidden" name="uri_role" id="deleteRoleUri" value="">
                    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash me-2"></i>Delete Role
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
