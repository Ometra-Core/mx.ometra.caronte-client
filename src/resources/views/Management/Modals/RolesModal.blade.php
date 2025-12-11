<div class="modal fade" id="rolesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Insert or delete roles</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="exampleDataList" class="form-label">Selecciona o busca un rol</label>

                    <input class="form-control" list="rolesUser" id="rolesUserInput" placeholder="Type to search...">
                    <datalist id="rolesUser">
                    </datalist>
                </div>
                <table class="table-bordered w-100">
                    <thead>
                        <th colspan="4" class="text-center" style="background-color: #CAD2C5; height: 60px;">
                            Roles of the user: <span id="spanUserName" class="fw-bold"></span>
                        </th>
                        <tr>
                            <th scope="col" class="text-center">Name</th>
                            <th scope="col" class="text-center">Description</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody id="rolesTableBody">
                    </tbody>
                </table>

            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteRoleModal" id="btnDeleteAllRoles">
                    <i class="fa-solid fa-trash me-2"></i>
                    Desautorizar usuario
                </button>
            </div>
        </div>
    </div>
</div>
