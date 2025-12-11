<div class="modal fade" id="confirmRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="textHeader"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('caronte.management.attach-roles') }}" id="confirmRoleFormAttach">
                    @csrf
                    <input type="hidden" id="confirmRoleUserId" name="userId" value="">

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn custom-btn-submit px-5 py-2" id="btnConfirmAddRole">
                            Confirm
                        </button>

                        <button type="button" class="btn custom-btn-cancel px-5 py-2" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
