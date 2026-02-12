import React, { useEffect, useRef, useState } from "react";

function Modal({ open, title, onClose, children }) {
  if (!open) {
    return null;
  }

  return (
    <>
      <div
        className="modal fade show"
        style={{ display: "block" }}
        role="dialog"
        aria-modal="true"
      >
        <div className="modal-dialog modal-lg">
          <div className="modal-content">
            <div className="modal-header">
              <h1 className="modal-title fs-5">{title}</h1>
              <button
                type="button"
                className="btn-close"
                aria-label="Close"
                onClick={onClose}
              ></button>
            </div>
            <div className="modal-body">{children}</div>
          </div>
        </div>
      </div>
      <div className="modal-backdrop fade show"></div>
    </>
  );
}

export default function ManagementIndex({
  users = [],
  roles = [],
  routes = {},
  csrf_token,
}) {
  const [activeTab, setActiveTab] = useState("users");
  const [userList, setUserList] = useState(users);
  const [searchValue, setSearchValue] = useState("");
  const [includeExternal, setIncludeExternal] = useState(false);
  const typingTimer = useRef(null);

  const [showUserCreate, setShowUserCreate] = useState(false);
  const [showUserEdit, setShowUserEdit] = useState(false);
  const [showUserDelete, setShowUserDelete] = useState(false);

  const [showRoleCreate, setShowRoleCreate] = useState(false);
  const [showRoleEdit, setShowRoleEdit] = useState(false);
  const [showRoleDelete, setShowRoleDelete] = useState(false);

  const [editUser, setEditUser] = useState(null);
  const [deleteUser, setDeleteUser] = useState(null);
  const [editRole, setEditRole] = useState(null);
  const [deleteRole, setDeleteRole] = useState(null);

  const [createRolesSelected, setCreateRolesSelected] = useState([]);
  const [editRolesSelected, setEditRolesSelected] = useState([]);

  useEffect(() => {
    if (!searchValue) {
      setUserList(users);
      return;
    }

    if (typingTimer.current) {
      clearTimeout(typingTimer.current);
    }

    typingTimer.current = setTimeout(function () {
      fetchUsers(searchValue);
    }, 500);
  }, [searchValue, includeExternal]);

  function fetchUsers(query) {
    if (!routes.usersList) {
      return;
    }

    let urlSearch = `${routes.usersList}?search=${encodeURIComponent(query)}`;
    if (includeExternal) {
      urlSearch += "&usersApp=false";
    }

    fetch(urlSearch)
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        const result = JSON.parse(data.data || "[]");
        setUserList(Array.isArray(result) ? result : []);
      })
      .catch(function () {
        setUserList([]);
      });
  }

  function fetchUserRoles(userId) {
    if (!routes.userRolesList) {
      return Promise.resolve([]);
    }

    const url = routes.userRolesList.replace("USER_ID", userId);
    return fetch(url)
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        const result = JSON.parse(data.data || "[]");
        return result.map(function (role) {
          return role.uri_applicationRole;
        });
      })
      .catch(function () {
        return [];
      });
  }

  function toggleRoleSelection(list, uri) {
    if (list.includes(uri)) {
      return list.filter(function (value) {
        return value !== uri;
      });
    }

    return [...list, uri];
  }

  function openEditUser(user) {
    setEditUser(user);
    setEditRolesSelected([]);
    fetchUserRoles(user.uri_user).then(function (roleUris) {
      setEditRolesSelected(roleUris);
    });
    setShowUserEdit(true);
  }

  function openDeleteUser(user) {
    setDeleteUser(user);
    setShowUserDelete(true);
  }

  function openEditRole(role) {
    setEditRole(role);
    setShowRoleEdit(true);
  }

  function openDeleteRole(role) {
    setDeleteRole(role);
    setShowRoleDelete(true);
  }

  function handleCreateSubmit(event) {
    if (createRolesSelected.length === 0) {
      event.preventDefault();
      alert("Selecciona al menos un rol.");
    }
  }

  return (
    <div className="container py-5">
      <div className="row justify-content-center">
        <div className="col-lg-10 col-lg-6">
          <ul
            className="nav nav-pills nav-fill custom-tabs-container p-2 rounded-top"
            role="tablist"
          >
            <li className="nav-item" role="presentation">
              <button
                className={`nav-link custom-tab-link w-100 ${activeTab === "users" ? "active" : ""}`}
                type="button"
                onClick={() => setActiveTab("users")}
              >
                Users
              </button>
            </li>
            <li className="nav-item" role="presentation">
              <button
                className={`nav-link custom-tab-link w-100 ${activeTab === "roles" ? "active" : ""}`}
                type="button"
                onClick={() => setActiveTab("roles")}
              >
                Roles
              </button>
            </li>
          </ul>

          <div className="card custom-card border-0 shadow-sm">
            {activeTab === "users" && (
              <div className="tab-pane fade show active">
                <div
                  className="card-header text-center py-3"
                  style={{ backgroundColor: "#d1dcd0" }}
                >
                  <h5 className="mb-0 fw-normal fs-5">User Management</h5>
                </div>
                <div className="card-body p-4">
                  <div className="d-flex justify-content-between align-items-center mb-3">
                    <div className="w-100">
                      <label
                        htmlFor="searchUser"
                        className="form-label fw-semibold"
                      >
                        Search user:
                      </label>
                      <div className="form-check form-switch">
                        <input
                          className="form-check-input"
                          type="checkbox"
                          role="switch"
                          id="switchCheckDefault"
                          checked={includeExternal}
                          onChange={(event) =>
                            setIncludeExternal(event.target.checked)
                          }
                        />
                        <label
                          className="form-check-label"
                          htmlFor="switchCheckDefault"
                        >
                          Incluir usuarios ajenos a la aplicacion
                        </label>
                      </div>
                      <div className="input-group">
                        <input
                          type="text"
                          className="form-control"
                          id="searchUser"
                          placeholder="Type name or email..."
                          value={searchValue}
                          onChange={(event) =>
                            setSearchValue(event.target.value)
                          }
                        />
                        <button
                          className="btn btn-outline-secondary"
                          type="button"
                        >
                          <i className="fa-solid fa-magnifying-glass"></i>
                        </button>
                      </div>
                    </div>
                    <div className="ms-3">
                      <button
                        type="button"
                        className="btn btn-primary"
                        onClick={() => setShowUserCreate(true)}
                      >
                        <i className="fa-solid fa-plus me-2"></i>Create user
                      </button>
                    </div>
                  </div>

                  <div className="table-responsive border rounded">
                    <table className="table table-hover mb-0 text-center align-middle">
                      <thead className="table-light">
                        <tr>
                          <th scope="col" className="border-end">
                            Name
                          </th>
                          <th scope="col" className="border-end">
                            Email
                          </th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        {userList.length === 0 && (
                          <tr>
                            <td
                              colSpan="3"
                              className="text-center text-muted py-4"
                            >
                              No users found.
                            </td>
                          </tr>
                        )}
                        {userList.map((user) => (
                          <tr key={user.uri_user}>
                            <td className="border-end text-start ps-3">
                              {user.name}
                            </td>
                            <td className="border-end text-start ps-3">
                              {user.email}
                            </td>
                            <td>
                              <button
                                className="btn btn-sm btn-link text-dark"
                                title="Edit"
                                onClick={() => openEditUser(user)}
                              >
                                <i className="fa-solid fa-pen-to-square fs-5"></i>
                              </button>
                              <button
                                className="btn btn-sm btn-link text-danger"
                                title="Delete"
                                onClick={() => openDeleteUser(user)}
                              >
                                <i className="fa-solid fa-trash fs-5"></i>
                              </button>
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            )}

            {activeTab === "roles" && (
              <div className="tab-pane fade show active">
                <div
                  className="card-header text-center py-3"
                  style={{ backgroundColor: "#d1dcd0" }}
                >
                  <h5 className="mb-0 fw-normal fs-5">Role Management</h5>
                </div>
                <div className="card-body p-4">
                  <div className="mb-3 d-flex justify-content-between align-items-center">
                    <h6 className="fw-semibold">Registered Roles</h6>
                    <button
                      type="button"
                      className="btn btn-primary"
                      onClick={() => setShowRoleCreate(true)}
                    >
                      <i className="fa-solid fa-plus me-2"></i>Create New Role
                    </button>
                  </div>

                  <div className="table-responsive border rounded">
                    <table className="table table-hover mb-0 align-middle">
                      <thead className="table-light">
                        <tr>
                          <th
                            scope="col"
                            className="border-end"
                            style={{ width: "25%" }}
                          >
                            Role Name
                          </th>
                          <th
                            scope="col"
                            className="border-end"
                            style={{ width: "50%" }}
                          >
                            Description
                          </th>
                          <th
                            scope="col"
                            className="text-center"
                            style={{ width: "25%" }}
                          >
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        {roles.length === 0 && (
                          <tr>
                            <td
                              colSpan="3"
                              className="text-center text-muted py-4"
                            >
                              No roles registered yet.
                            </td>
                          </tr>
                        )}
                        {roles.map((role) => (
                          <tr key={role.uri_applicationRole}>
                            <td className="border-end ps-3">
                              <strong>{role.name || "N/A"}</strong>
                            </td>
                            <td className="border-end ps-3">
                              {role.description || "No description"}
                            </td>
                            <td className="text-center">
                              <button
                                className="btn btn-sm btn-link text-dark"
                                title="Edit role"
                                onClick={() => openEditRole(role)}
                              >
                                <i className="fa-solid fa-pen-to-square fs-5"></i>
                              </button>
                              <button
                                className="btn btn-sm btn-link text-danger"
                                title="Delete role"
                                onClick={() => openDeleteRole(role)}
                              >
                                <i className="fa-solid fa-trash fs-5"></i>
                              </button>
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>

      <Modal
        open={showUserCreate}
        title="Create a new user"
        onClose={() => setShowUserCreate(false)}
      >
        <form
          method="POST"
          action={routes.usersCreate || ""}
          id="userCreateForm"
          onSubmit={handleCreateSubmit}
        >
          <input type="hidden" name="_token" value={csrf_token} />
          <div className="mb-3">
            <label htmlFor="createNameInput" className="form-label fw-semibold">
              Name
            </label>
            <input
              type="text"
              className="form-control"
              id="createNameInput"
              name="name"
              required
            />
          </div>
          <div className="mb-3">
            <label
              htmlFor="createEmailInput"
              className="form-label fw-semibold"
            >
              Email
            </label>
            <input
              type="email"
              className="form-control"
              id="createEmailInput"
              name="email"
              required
            />
          </div>
          <div className="mb-3">
            <label className="form-label fw-semibold">Roles</label>
            <div className="row">
              {roles.length === 0 && (
                <div className="col-12">
                  <div className="text-muted">No roles available.</div>
                </div>
              )}
              {roles.map((role) => (
                <div className="col-12 col-md-6" key={role.uri_applicationRole}>
                  <div className="form-check">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      name="roles[]"
                      value={role.uri_applicationRole}
                      checked={createRolesSelected.includes(
                        role.uri_applicationRole,
                      )}
                      onChange={() =>
                        setCreateRolesSelected((current) =>
                          toggleRoleSelection(
                            current,
                            role.uri_applicationRole,
                          ),
                        )
                      }
                    />
                    <label className="form-check-label">
                      {role.name}
                      {role.description && (
                        <small className="text-muted ms-1">
                          {role.description}
                        </small>
                      )}
                    </label>
                  </div>
                </div>
              ))}
            </div>
          </div>
          <div className="d-flex justify-content-end gap-2">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => setShowUserCreate(false)}
            >
              Cancel
            </button>
            <button type="submit" className="btn btn-primary">
              Create user
            </button>
          </div>
        </form>
      </Modal>

      <Modal
        open={showUserEdit}
        title="Edit user"
        onClose={() => setShowUserEdit(false)}
      >
        <form method="POST" action={routes.usersUpdate || ""} id="userEditForm">
          <input type="hidden" name="_token" value={csrf_token} />
          <input
            type="hidden"
            name="uri_user"
            value={editUser?.uri_user || ""}
          />
          <div className="mb-3">
            <label htmlFor="editNameInput" className="form-label fw-semibold">
              Name
            </label>
            <input
              type="text"
              className="form-control"
              id="editNameInput"
              name="name"
              defaultValue={editUser?.name || ""}
              required
            />
          </div>
          <div className="mb-3">
            <label htmlFor="editEmailInput" className="form-label fw-semibold">
              Email
            </label>
            <input
              type="email"
              className="form-control"
              id="editEmailInput"
              value={editUser?.email || ""}
              disabled
            />
          </div>
          <div className="mb-3">
            <label className="form-label fw-semibold">Roles</label>
            <div className="row">
              {roles.length === 0 && (
                <div className="col-12">
                  <div className="text-muted">No roles available.</div>
                </div>
              )}
              {roles.map((role) => (
                <div className="col-12 col-md-6" key={role.uri_applicationRole}>
                  <div className="form-check">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      name="roles[]"
                      value={role.uri_applicationRole}
                      checked={editRolesSelected.includes(
                        role.uri_applicationRole,
                      )}
                      onChange={() =>
                        setEditRolesSelected((current) =>
                          toggleRoleSelection(
                            current,
                            role.uri_applicationRole,
                          ),
                        )
                      }
                    />
                    <label className="form-check-label">
                      {role.name}
                      {role.description && (
                        <small className="text-muted ms-1">
                          {role.description}
                        </small>
                      )}
                    </label>
                  </div>
                </div>
              ))}
            </div>
          </div>
          <div className="d-flex justify-content-end gap-2">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => setShowUserEdit(false)}
            >
              Cancel
            </button>
            <button type="submit" className="btn btn-primary">
              Save changes
            </button>
          </div>
        </form>
      </Modal>

      <Modal
        open={showUserDelete}
        title="Delete user"
        onClose={() => setShowUserDelete(false)}
      >
        <form
          method="POST"
          action={routes.usersDelete || ""}
          id="userDeleteForm"
        >
          <input type="hidden" name="_token" value={csrf_token} />
          <input
            type="hidden"
            name="uri_user"
            value={deleteUser?.uri_user || ""}
          />
          <p className="mb-4">
            Are you sure you want to delete{" "}
            <strong>{deleteUser?.name || ""}</strong>?
          </p>
          <div className="d-flex justify-content-end gap-2">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => setShowUserDelete(false)}
            >
              Cancel
            </button>
            <button type="submit" className="btn btn-danger">
              Delete user
            </button>
          </div>
        </form>
      </Modal>

      <Modal
        open={showRoleCreate}
        title="Create new role"
        onClose={() => setShowRoleCreate(false)}
      >
        <form method="POST" action={routes.rolesCreate || ""}>
          <input type="hidden" name="_token" value={csrf_token} />
          <div className="mb-3">
            <label htmlFor="roleNameInput" className="form-label fw-semibold">
              Role Name:
            </label>
            <input
              type="text"
              className="form-control"
              id="roleNameInput"
              name="name"
              required
            />
            <small className="form-text text-muted">
              Alphanumeric only, no spaces (e.g., admin, editor, moderator)
            </small>
          </div>
          <div className="mb-3">
            <label
              htmlFor="roleDescriptionInput"
              className="form-label fw-semibold"
            >
              Description:
            </label>
            <textarea
              name="description"
              className="form-control"
              id="roleDescriptionInput"
              rows="3"
            ></textarea>
            <small className="form-text text-muted">
              Optional: Brief description of role purpose
            </small>
          </div>
          <div className="d-flex justify-content-end gap-2">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => setShowRoleCreate(false)}
            >
              Cancel
            </button>
            <button type="submit" className="btn btn-primary">
              Create Role
            </button>
          </div>
        </form>
      </Modal>

      <Modal
        open={showRoleEdit}
        title="Edit role"
        onClose={() => setShowRoleEdit(false)}
      >
        <form method="POST" action={routes.rolesUpdate || ""}>
          <input type="hidden" name="_token" value={csrf_token} />
          <input
            type="hidden"
            name="uri_role"
            value={editRole?.uri_applicationRole || ""}
          />
          <div className="mb-3">
            <label htmlFor="roleNameDisplay" className="form-label fw-semibold">
              Role Name:
            </label>
            <input
              type="text"
              className="form-control"
              id="roleNameDisplay"
              value={editRole?.name || ""}
              disabled
            />
            <small className="form-text text-muted">
              Role names cannot be changed
            </small>
          </div>
          <div className="mb-3">
            <label
              htmlFor="roleDescriptionUpdate"
              className="form-label fw-semibold"
            >
              Description:
            </label>
            <textarea
              name="description"
              className="form-control"
              id="roleDescriptionUpdate"
              rows="3"
              defaultValue={editRole?.description || ""}
            ></textarea>
            <small className="form-text text-muted">
              Update the role description
            </small>
          </div>
          <div className="d-flex justify-content-end gap-2">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => setShowRoleEdit(false)}
            >
              Cancel
            </button>
            <button type="submit" className="btn btn-primary">
              Save Changes
            </button>
          </div>
        </form>
      </Modal>

      <Modal
        open={showRoleDelete}
        title="Confirm Role Deletion"
        onClose={() => setShowRoleDelete(false)}
      >
        <form method="POST" action={routes.rolesDelete || ""}>
          <input type="hidden" name="_token" value={csrf_token} />
          <input
            type="hidden"
            name="uri_role"
            value={deleteRole?.uri_applicationRole || ""}
          />
          <div className="alert alert-warning" role="alert">
            <strong>Warning:</strong> This action cannot be undone.
          </div>
          <p>
            Are you sure you want to delete the role{" "}
            <strong>{deleteRole?.name || ""}</strong>?
          </p>
          <p className="text-muted small">
            All users with this role will lose it. If this is the last role
            assigned to any user, they may lose access to the application.
          </p>
          <div className="d-flex justify-content-end gap-2">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => setShowRoleDelete(false)}
            >
              Cancel
            </button>
            <button type="submit" className="btn btn-danger">
              Delete Role
            </button>
          </div>
        </form>
      </Modal>
    </div>
  );
}
