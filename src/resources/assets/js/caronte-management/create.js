document.addEventListener('DOMContentLoaded', function () {
    const rolesModal = document.getElementById('rolesModal');

    rolesModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;
        const userId = button.getAttribute('data-userId');
        console.log(userId);
        const userName = button.getAttribute('data-userName');
        const spanUserName = document.getElementById('spanUserName');
        spanUserName.textContent = userName;
        var textConfirm = '';

        $url = `/caronte-client-management/roles-user/${userId}`;
        console.log($url);
        fetch($url)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('rolesTableBody');

                tableBody.innerHTML = '';
                let roles = JSON.parse(data.data);

                if (roles && roles.length > 0) {

                    roles.forEach(role => {
                        const row = `
                            <tr>
                                <td class="text-center">${role.name}</td>
                                <td class="text-center">${role.description || 'N/A'}</td>
                                <td class="text-center">
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            title="Eliminar rol"
                                            data-uri-role="${role.uri_applicationRole}" 
                                            data-role-name="${role.name}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#confirmDeleteRoleModal">
                                        <i class="fa-solid fa-trash fs-4"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });

                } else {
                    tableBody.innerHTML = '<tr><td colspan="3" class="text-center">El usuario no tiene roles asignados</td></tr>';
                }
            })
            .catch(error => {
            });

        //lista de opciones de roles
        const dataList = document.getElementById('rolesUser');
        dataList.innerHTML = '';
        $urlRoles = '/caronte-client-management/all-roles';
        fetch($urlRoles)
            .then(response => response.json())
            .then(data => {
                let rolesList = JSON.parse(data);
                rolesList.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role.name;
                    option.setAttribute('data-uri-application-role', role.uri_applicationRole);
                    dataList.appendChild(option);
                });
            })
            .catch(error => {
            });

        //enlazar roles

        let selectedRoleData = {
            name: '',
            uri: ''
        };

        const roleInput = document.getElementById('rolesUserInput');

        roleInput.addEventListener('input', function () {
            const val = this.value;

            const optionFound = Array.from(dataList.options).find(opt => opt.value === val);

            if (optionFound) {
                selectedRoleData.name = val;
                selectedRoleData.uri = optionFound.getAttribute('data-uri-application-role');
                textConfirm = `Are you sure you want to add the role "${selectedRoleData.name}" to the user "${userName}"?`;

                const confirmModal = new bootstrap.Modal(document.getElementById('confirmRoleModal'));
                confirmModal.show();

                this.value = '';
                this.blur();
            }
        });

        let confirmRoleModal = document.getElementById('confirmRoleModal');

        confirmRoleModal.addEventListener('show.bs.modal', function (event) {

            let textHeader = document.getElementById('textHeader');
            textHeader.textContent = textConfirm;
            formAttach = document.getElementById('confirmRoleFormAttach');
            formAttach.innerHTML += `
                <input type="hidden" name="uri_rol" id="roleUri" value="${selectedRoleData.uri}">
                <input type="hidden" name="uri_user" id="userUri" value="${userId}">
            `;
        });

        let confirmDeleteRoleModal = document.getElementById('confirmDeleteRoleModal');

        confirmDeleteRoleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const uriRole = button.getAttribute('data-uri-role');
            const roleName = button.getAttribute('data-role-name');
            let textHeaderDelete = document.getElementById('textHeaderConfirmDelete');

            if (!uriRole) {
                textHeaderDelete.textContent = `Are you sure you want to delete all roles from the user "${userName}"?`;
                formDelete = document.getElementById('confirmRoleDelete');
                formDelete.innerHTML += `
                <input type="hidden" name="allRoles" id="deleteRoleUri" value="${true}">
                <input type="hidden" name="uri_user" id="deleteUserUri" value="${userId}">
            `;
            } else {

                textHeaderDelete.textContent = `Are you sure you want to delete the role "${roleName}" from the user "${userName}"?`;

                formDelete = document.getElementById('confirmRoleDelete');
                formDelete.innerHTML += `
                <input type="hidden" name="uri_rol" id="deleteRoleUri" value="${uriRole}">
                <input type="hidden" name="uri_user" id="deleteUserUri" value="${userId}">
            `;
            }
        });
    });

    const managesModal = document.getElementById('managesModal');

    managesModal.addEventListener('show.bs.modal', function (event) {
        let buttonSave = document.getElementById('saveRoleButton');
        let buttonEdit = document.getElementById('btnUpdateUser');
        const spanManageUserName = document.getElementById('nameUpdate');
        const spanManageUserEmail = document.getElementById('emailUpdate');

        buttonSave.classList.add('d-none');

        buttonEdit.classList.remove('d-none');

        spanManageUserName.disabled = true;
        spanManageUserEmail.disabled = true;

        const button = event.relatedTarget;
        let userName2 = button.getAttribute('data-userName');
        let userEmail2 = button.getAttribute('data-user-email');
        userId = button.getAttribute('data-userId');

        spanManageUserName.value = userName2;
        spanManageUserEmail.value = userEmail2;

        buttonEdit.onclick = function () {
            spanManageUserName.disabled = false;

            buttonSave.classList.remove('d-none');

            this.classList.add('d-none');

            spanManageUserName.focus();
        }

        document.getElementById('manageUserUri').value = userId;

        //elliminar al usuario
        let buttonDeleteUser = document.getElementById('btnDeleteUser');
        buttonDeleteUser.addEventListener('click', function () {
            //mostrar modal de confirmacion

            const deleteUserModal = new bootstrap.Modal(document.getElementById('confirmDeleteUser'));
            deleteUserModal.show();
        });

        let confirmDeleteUserModal = document.getElementById('confirmDeleteUser');

        confirmDeleteUserModal.addEventListener('show.bs.modal', function (event) {
            let textHeaderDeleteUser = document.getElementById('textHeaderDeleteUser');
            textHeaderDeleteUser.textContent = `Are you sure you want to delete the user "${userName2}"?`;

            let inputDeleteUserUri = document.getElementById('uri_userDelete');
            inputDeleteUserUri.value = userId;
        });
    });

    //roles para la creacion de usuarios
    selectRolesUser = document.getElementById('selectRolesUser');
    // selectRolesUser.innerHTML = '';
    $urlRoles = '/caronte-client-management/all-roles';
    fetch($urlRoles)
        .then(response => response.json())
        .then(data => {
            let rolesList = JSON.parse(data);
            console.log(rolesList);
            rolesList.forEach(role => {
                const selectOption = document.createElement('option');
                selectOption.value = role.uri_applicationRole;
                selectOption.textContent = role.name;
                selectRolesUser.appendChild(selectOption);
            });
        })
        .catch(error => {
        });






    const searchInput = document.getElementById('searchUser');
    const switchCheck = document.getElementById('switchCheckDefault');
    const listContainer = document.getElementById('usersListContainer');
    const originalBladeContent = listContainer.innerHTML;

    let typingTimer;
    const doneTypingInterval = 500;

    searchInput.addEventListener('input', function () {
        handleSearch();
    });

    switchCheck.addEventListener('change', function () {
        if (searchInput.value.trim() !== '') {
            fetchUsers(searchInput.value.trim());
        }
    });

    function handleSearch() {
        clearTimeout(typingTimer);
        const query = searchInput.value.trim();

        if (query === '') {
            listContainer.innerHTML = originalBladeContent;
            return;
        }

        typingTimer = setTimeout(() => {
            fetchUsers(query);
        }, doneTypingInterval);
    }

    function fetchUsers(query) {
        listContainer.innerHTML = `
            <tr>
                <td colspan="3" class="text-center p-3">
                    <i class="fas fa-spinner fa-spin"></i> Buscando...
                </td>
            </tr>`;

        let urlSearch = `/caronte-client-management/users?search=${encodeURIComponent(query)}`;

        const includeExternalUsers = switchCheck.checked;
        if (includeExternalUsers) {
            urlSearch += `&usersApp=false`;
        }

        console.log("URL de búsqueda:", urlSearch);
        fetch(urlSearch)
            .then(response => response.json())
            .then(data => {
                if (searchInput.value.trim() === '') {
                    listContainer.innerHTML = originalBladeContent;
                    return;
                }

                console.log("Respuesta recibida:", data);

                const users = JSON.parse(data.data);
                console.log("Usuarios procesados:", users);

                if (!Array.isArray(users) || users.length === 0) {
                    listContainer.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center p-3">
                                <div class="alert alert-warning m-0">No se encontraron usuarios.</div>
                            </td>
                        </tr>`;
                    return;
                }

                let newHtml = '';

                users.forEach(user => {
                    newHtml += `
                        <tr>
                            <td class="border-end text-start ps-3">${user.name}</td>
                            <td class="border-end text-start ps-3">${user.email}</td>
                            <td>
                                <button class="btn btn-sm btn-link text-dark" title="Settings"
                                    data-userId="${user.uri_user}" 
                                    data-userName="${user.name}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rolesModal">
                                    <i class="fa-solid fa-gear fs-5"></i>
                                </button>

                                <button class="btn btn-sm btn-link text-dark" title="Edit" 
                                    data-bs-toggle="modal"
                                    data-userId="${user.uri_user}" 
                                    data-userName="${user.name}"
                                    data-user-email="${user.email}" 
                                    data-bs-target="#managesModal">
                                    <i class="fa-solid fa-pen-to-square fs-5"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                listContainer.innerHTML = newHtml;
            })
            .catch(error => {
                console.error('Error:', error);
                listContainer.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center p-3">
                            <div class="alert alert-danger m-0">Error al buscar usuarios.</div>
                        </td>
                    </tr>`;
            });
    }
});
