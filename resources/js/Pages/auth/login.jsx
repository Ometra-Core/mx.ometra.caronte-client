import React from "react";

export default function Login({ callback_url, routes = {}, csrf_token }) {
  return (
    <div className="container min-vh-100 d-flex justify-content-center align-items-center">
      <div className="row w-100 justify-content-center">
        <div className="col-md-8 col-lg-5">
          <div className="card shadow-lg border-0 rounded-lg">
            <div className="card-header bg-primary text-white text-center py-4">
              <h3 className="font-weight-light my-2">INICIAR SESION</h3>
            </div>
            <div className="card-body p-5">
              <form method="POST" action={routes.login || ""}>
                <input type="hidden" name="_token" value={csrf_token} />
                {callback_url && (
                  <input
                    type="hidden"
                    name="callback_url"
                    value={callback_url}
                  />
                )}
                <div className="mb-3">
                  <label htmlFor="email" className="form-label text-muted">
                    Correo electronico
                  </label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    className="form-control form-control-lg"
                    placeholder="nombre@ejemplo.com"
                    required
                  />
                </div>
                <div className="mb-3">
                  <label htmlFor="password" className="form-label text-muted">
                    Contrasena
                  </label>
                  <input
                    type="password"
                    id="password"
                    name="password"
                    className="form-control form-control-lg"
                    placeholder="********"
                    required
                  />
                </div>
                <div className="d-flex justify-content-end mb-4">
                  <a
                    href={routes.passwordRecoverForm || "/password/recover"}
                    className="text-decoration-none small text-muted"
                  >
                    Olvidaste tu contrasena?
                  </a>
                </div>
                <div className="d-grid gap-2">
                  <input
                    type="submit"
                    value="Entrar"
                    className="btn btn-primary btn-lg"
                  />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
