document.addEventListener("DOMContentLoaded", function () {
  const usersManagement = document.getElementById("usersManagement");
  if (!usersManagement) {
    return;
  }

  const usersUrl = usersManagement.dataset.usersUrl;
  const userRolesUrlTemplate = usersManagement.dataset.userRolesUrlTemplate;

  const userCreateModal = document.getElementById("userCreateModal");
  const userEditModal = document.getElementById("userEditModal");
  const userDeleteModal = document.getElementById("userDeleteModal");
  const usersListContainer = document.getElementById("usersListContainer");

  const searchInput = document.getElementById("searchUser");
  const switchCheck = document.getElementById("switchCheckDefault");
  const originalBladeContent = usersListContainer.innerHTML;

  const roleCheckboxSelector = ".user-role-checkbox";

  function clearRoleCheckboxes(modalElement) {
    if (!modalElement) {
      return;
    }

    modalElement
      .querySelectorAll(roleCheckboxSelector)
      .forEach(function (checkbox) {
        checkbox.checked = false;
      });
  }

  function applyRoleCheckboxes(modalElement, roleUris) {
    if (!modalElement) {
      return;
    }

    const selectedSet = new Set(roleUris);
    modalElement
      .querySelectorAll(roleCheckboxSelector)
      .forEach(function (checkbox) {
        checkbox.checked = selectedSet.has(checkbox.dataset.roleUri);
      });
  }

  function fetchUserRoles(userId) {
    const url = userRolesUrlTemplate.replace("USER_ID", userId);

    return fetch(url)
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        const roles = JSON.parse(data.data || "[]");
        return roles.map(function (role) {
          return role.uri_applicationRole;
        });
      })
      .catch(function () {
        return [];
      });
  }

  if (userCreateModal) {
    userCreateModal.addEventListener("show.bs.modal", function () {
      const form = document.getElementById("userCreateForm");
      if (form) {
        form.reset();
      }

      clearRoleCheckboxes(userCreateModal);
    });
  }

  if (userEditModal) {
    userEditModal.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      const userId = button.getAttribute("data-user-id");
      const userName = button.getAttribute("data-user-name");
      const userEmail = button.getAttribute("data-user-email");

      document.getElementById("editUserUri").value = userId;
      document.getElementById("editNameInput").value = userName;
      document.getElementById("editEmailInput").value = userEmail;

      clearRoleCheckboxes(userEditModal);
      fetchUserRoles(userId).then(function (roleUris) {
        applyRoleCheckboxes(userEditModal, roleUris);
      });
    });
  }

  if (userDeleteModal) {
    userDeleteModal.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      const userId = button.getAttribute("data-user-id");
      const userName = button.getAttribute("data-user-name");

      document.getElementById("deleteUserUri").value = userId;
      document.getElementById("deleteUserName").textContent = userName;
    });
  }

  if (userCreateModal) {
    const userCreateForm = document.getElementById("userCreateForm");
    if (userCreateForm) {
      userCreateForm.addEventListener("submit", function (event) {
        const checkedRoles = userCreateForm.querySelectorAll(
          roleCheckboxSelector + ":checked",
        );

        if (checkedRoles.length === 0) {
          event.preventDefault();
          alert("Please select at least one role.");
        }
      });
    }
  }

  let typingTimer;
  const doneTypingInterval = 500;

  searchInput.addEventListener("input", function () {
    handleSearch();
  });

  switchCheck.addEventListener("change", function () {
    if (searchInput.value.trim() !== "") {
      fetchUsers(searchInput.value.trim());
    }
  });

  function handleSearch() {
    clearTimeout(typingTimer);
    const query = searchInput.value.trim();

    if (query === "") {
      usersListContainer.innerHTML = originalBladeContent;
      return;
    }

    typingTimer = setTimeout(function () {
      fetchUsers(query);
    }, doneTypingInterval);
  }

  function fetchUsers(query) {
    usersListContainer.innerHTML = `
            <tr>
                <td colspan="3" class="text-center p-3">
                    <i class="fas fa-spinner fa-spin"></i> Buscando...
                </td>
            </tr>`;

    let urlSearch = `${usersUrl}?search=${encodeURIComponent(query)}`;

    if (switchCheck.checked) {
      urlSearch += `&usersApp=false`;
    }

    fetch(urlSearch)
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        if (searchInput.value.trim() === "") {
          usersListContainer.innerHTML = originalBladeContent;
          return;
        }

        const users = JSON.parse(data.data || "[]");

        if (!Array.isArray(users) || users.length === 0) {
          usersListContainer.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center p-3">
                                <div class="alert alert-warning m-0">No se encontraron usuarios.</div>
                            </td>
                        </tr>`;
          return;
        }

        let newHtml = "";

        users.forEach(function (user) {
          newHtml += `
                        <tr>
                            <td class="border-end text-start ps-3">${user.name}</td>
                            <td class="border-end text-start ps-3">${user.email}</td>
                            <td>
                                <button class="btn btn-sm btn-link text-dark" title="Edit" data-bs-toggle="modal"
                                    data-user-id="${user.uri_user}" data-user-name="${user.name}"
                                    data-user-email="${user.email}" data-bs-target="#userEditModal">
                                    <i class="fa-solid fa-pen-to-square fs-5"></i>
                                </button>

                                <button class="btn btn-sm btn-link text-danger" title="Delete" data-bs-toggle="modal"
                                    data-user-id="${user.uri_user}" data-user-name="${user.name}"
                                    data-bs-target="#userDeleteModal">
                                    <i class="fa-solid fa-trash fs-5"></i>
                                </button>
                            </td>
                        </tr>
                    `;
        });

        usersListContainer.innerHTML = newHtml;
      })
      .catch(function () {
        usersListContainer.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center p-3">
                            <div class="alert alert-danger m-0">Error al buscar usuarios.</div>
                        </td>
                    </tr>`;
      });
  }
});
