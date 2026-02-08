import React from "react";

export default function TwoFactor({ callback_url, routes = {}, csrf_token }) {
  return (
    <div className="mt-5 col-6 mx-auto">
      <form method="POST" action={routes.twoFactorRequest || ""}>
        <input type="hidden" name="_token" value={csrf_token} />
        {callback_url && (
          <input type="hidden" name="callback_url" value={callback_url} />
        )}
        <div className="form-group mt-5">
          <h4>Correo electronico registrado</h4>
          <div className="d-flex flex-row">
            <input
              type="email"
              name="email"
              className="form-control"
              placeholder="Correo electronico"
              required
            />
            <input
              type="submit"
              value="Entrar"
              className="btn btn-success ms-2"
            />
          </div>
        </div>
      </form>
    </div>
  );
}
