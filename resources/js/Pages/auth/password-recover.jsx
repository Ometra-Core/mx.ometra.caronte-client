import React from "react";

export default function PasswordRecover({
  user,
  callback_url,
  routes = {},
  csrf_token,
}) {
  return (
    <div className="container min-vh-100 d-flex justify-content-center align-items-center">
      <div className="row w-100 justify-content-center">
        <div className="col-md-8 col-lg-5">
          <div className="card shadow-lg border-0 rounded-lg">
            <div className="card-header bg-primary text-white text-center py-4">
              <h3 className="font-weight-light my-2">
                Hola {user?.name || ""}
              </h3>
            </div>
            <div className="card-body p-5">
              <form method="POST" action={routes.passwordRecoverSubmit || ""}>
                <input type="hidden" name="_token" value={csrf_token} />
                {callback_url && (
                  <input
                    type="hidden"
                    name="callback_url"
                    value={callback_url}
                  />
                )}
                <div className="form-group mt-3 text-center">
                  <label htmlFor="new_password">Nueva contrasena:</label>
                  <input
                    className="form-control mt-2"
                    type="password"
                    name="password"
                    required
                    autoFocus
                  />
                </div>
                <div className="form-group mt-3 text-center">
                  <label htmlFor="new_password_confirm">
                    Confirma tu contrasena:
                  </label>
                  <input
                    className="form-control mt-2"
                    type="password"
                    name="password_confirmation"
                    required
                  />
                </div>
                <div className="form-group mt-4 d-flex justify-content-center">
                  <button type="submit" className="btn btn-primary">
                    Enviar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
