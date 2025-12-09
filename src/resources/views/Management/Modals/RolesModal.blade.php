<div class="modal fade" id="rolesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Insert or delete roles</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- <form id="rolesForm" data-applications-roles="{{ $applicationsRoles }}">
                    @csrf
                    <div class="mb-3">
                        <label for="application" class="form-label">Aplicación</label>
                        <select class="form-select" id="application" name="app_id">
                            <option value="" selected>Seleccione la app</option>
                            @foreach ($applications as $application)
                                <option value="{{ $application->getID() }}">{{ $application->getName() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rol</label>
                        <select class="form-select" id="role" name="uri_applicationRole">
                            <option value="" selected>Seleccione rol</option>
                        </select>
                    </div>
                </form> --}}
                <div class="mb-3">
                    <label for="exampleDataList" class="form-label">Selecciona o busca un usuario</label>

                    <input class="form-control" list="datalistOptions" id="exampleDataList"
                        placeholder="Type to search...">
                    <datalist id="datalistOptions">
                        <option value="User 1">
                        <option value="User 2">
                        <option value="User 3">
                        <option value="User 4">
                    </datalist>
                </div>
                <table class="table-bordered w-100">
                    <thead>
                        <th colspan="4" class="text-center" style="background-color: #CAD2C5; height: 60px;">
                            Roles of the user: user1
                        </th>
                        <tr>
                            <th scope="col" class="text-center">Name</th>
                            <th scope="col" class="text-center">Description</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Mark</td>
                            <td class="text-center">Otto</td>
                            <td class="text-center">
                                <button class="btn btn-sm" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#">
                                    <i class="fa-solid fa-trash fs-4"></i>
                                </button>

                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-secondary" id="saveRoleButton">Guardar</button>
            </div>
        </div>
    </div>
</div>
