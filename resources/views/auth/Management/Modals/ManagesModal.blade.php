<div class="modal fade" id="managesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">General information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('caronte.management.update-user') }}" id="manageUserForm">
                    @csrf
                    <input type="hidden" name="uri_user" id="manageUserUri" value="">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Name:</label>
                        <input name="name" type="text" class="form-control" id="nameUpdate" value="">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email:</label>
                        <input name="email" type="email" class="form-control" id="emailUpdate" value="">
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-secondary" id="saveRoleButton">Guardar</button>

                    <button type="button" class="btn btn-info" id="btnUpdateUser">
                        <i class="fa-solid fa-pen-to-square me-2"></i>
                    </button>
                    {{-- <button type="button" class="btn btn-danger" id="btnDeleteUser">
                        <i class="fa-solid fa-trash me-2"></i>
                    </button> --}}
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
