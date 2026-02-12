<div class="tab-pane fade show active" id="create-content" role="tabpanel" aria-labelledby="create-tab">

    <div class="card-header custom-card-header text-center py-3" style="background-color: #d1dcd0;">
        <h5 class="mb-0 fw-normal fs-5">Create a new user</h5>
    </div>

    <div class="card-body p-4">
        <form method="POST" action="{{ route('caronte.management.store') }}">
            @csrf
            <div class="mb-4">
                <label for="nameInput" class="form-label fw-semibold">Name</label>
                <input type="text" class="form-control custom-input py-2" id="nameInput" name="name">
            </div>

            <div class="mb-5">
                <label for="emailInput" class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control custom-input py-2" id="emailInput" name="email">
            </div>
            <div class="mb-5">
                <label for="emailInput" class="form-label fw-semibold">Select the role to attach: </label>
                <select class="form-select" id="selectRolesUser" aria-label="Default select example"
                    name="uri_applicationRole">
                    <option selected>Select a role</option>
                </select>
            </div>


            <div class="d-flex justify-content-between">
                <button type="submit" class="btn custom-btn-submit px-5 py-2">
                    Crear
                </button>

                <button type="button" class="btn custom-btn-cancel px-5 py-2">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
