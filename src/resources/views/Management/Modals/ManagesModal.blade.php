<div class="modal fade" id="managesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">General information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Name:</label>
                        <input name="nameUpdate" type="text" class="form-control" id="nameUpdate" value="">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email:</label>
                        <input name="emailUpdate" type="email" class="form-control" id="emailUpdate" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-secondary" id="saveRoleButton">Guardar</button>

                <button type="button" class="btn btn-info" id="updateUser">
                    <i class="fa-solid fa-pen-to-square me-2"></i>
                </button>
                <button type="button" class="btn btn-danger" id="deleteUser">
                    <i class="fa-solid fa-trash me-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>
