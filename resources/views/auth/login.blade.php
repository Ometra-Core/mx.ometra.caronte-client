@extends('caronte::layouts.base')

@section('content')
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-5">

                {{-- Tarjeta de Login --}}
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="font-weight-light my-2">INICIAR SESIÓN</h3>
                    </div>

                    <div class="card-body p-5">
                        <form method="POST">
                            @csrf

                            {{-- Input Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label text-muted">Correo electrónico</label>
                                <input type="email" id="email" name="email" value="{{ session('email') }}"
                                    class="form-control form-control-lg" placeholder="nombre@ejemplo.com" required>
                            </div>

                            {{-- Input Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label text-muted">Contraseña</label>
                                <input type="password" id="password" name="password" class="form-control form-control-lg"
                                    placeholder="********" required>
                            </div>

                            {{-- Link Olvidé contraseña --}}
                            <div class="d-flex justify-content-end mb-4">
                                <a href="/password/recover" class="text-decoration-none small text-muted">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>

                            {{-- Botón --}}
                            <div class="d-grid gap-2">
                                <input type="submit" value="Entrar" class="btn btn-primary btn-lg">
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
