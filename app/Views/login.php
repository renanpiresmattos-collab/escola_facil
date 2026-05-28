<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Acesse sua conta">
    <title>Escola Fácil</title>
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body>
    <main class="app-page d-grid p-4 p-md-5">
        <section class="app-shell container bg-white p-0 m-auto" aria-label="Login">
            <div class="row g-0">
                <aside class="app-brand-panel d-none d-md-flex col-md-5 p-5" aria-hidden="true">
                    <div class="d-flex flex-column justify-content-between h-100 position-relative z-1">
                        <div class="app-brand-mark">F</div>
                        <div>
                            <h1 class="display-4 fw-bold lh-1 mb-3">Escola Fácil</h1>
                            <p class="fs-5 lh-lg text-white-50 mb-0">Entre para consultar dados, acompanhar usuarios e continuar suas operacoes com seguranca.</p>
                        </div>
                    </div>
                </aside>

                <section class="col-12 col-md-7">
                    <div class="app-login-panel d-flex align-items-center py-4 py-md-5 px-4 px-md-5">
                        <div class="app-login-card w-100 mx-auto">
                            <div class="d-md-none mb-4">
                                <div class="app-brand-mark mb-3">F</div>
                                <p class="text-secondary mb-0">Escola Fácil</p>
                            </div>

                            <h1 class="fw-bold mb-2">Entrar</h1>
                            <p class="text-secondary mb-4">Use seu email e senha para acessar sua conta.</p>

                            <form id="login-form" novalidate>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="login-email">Email</label>
                                    <div class="input-group input-group-lg">
                                        <input class="form-control" id="login-email" name="email" type="email" inputmode="email" autocomplete="email" placeholder="voce@empresa.com" required>
                                        <span class="input-group-text app-field-icon text-secondary bg-white">
                                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M4 6.75A2.75 2.75 0 0 1 6.75 4h10.5A2.75 2.75 0 0 1 20 6.75v10.5A2.75 2.75 0 0 1 17.25 20H6.75A2.75 2.75 0 0 1 4 17.25V6.75Z" stroke="currentColor" stroke-width="1.8"/>
                                                <path d="m6.75 8 4.28 3.25a1.6 1.6 0 0 0 1.94 0L17.25 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="login-password">Senha</label>
                                    <div class="input-group input-group-lg">
                                        <input class="form-control" id="login-password" name="password" type="password" autocomplete="current-password" placeholder="Digite sua senha" required minlength="3">
                                        <button class="btn btn-outline-secondary app-password-button" id="password-toggle" type="button" aria-label="Mostrar senha">
                                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M2.75 12s3.25-6.25 9.25-6.25S21.25 12 21.25 12 18 18.25 12 18.25 2.75 12 2.75 12Z" stroke="currentColor" stroke-width="1.8"/>
                                                <path d="M12 14.75a2.75 2.75 0 1 0 0-5.5 2.75 2.75 0 0 0 0 5.5Z" stroke="currentColor" stroke-width="1.8"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 my-4">
                                    <div class="form-check">
                                        <input class="form-check-input" id="remember-access" type="checkbox" name="remember">
                                        <label class="form-check-label text-secondary" for="remember-access">Lembrar acesso</label>
                                    </div>
                                    <a class="link-primary fw-semibold text-decoration-none" href="#" aria-label="Recuperar senha">Esqueci minha senha</a>
                                </div>

                                <button class="btn btn-primary btn-lg w-100 fw-bold d-inline-flex align-items-center justify-content-center gap-2" id="login-submit" type="submit">
                                    <span class="app-submit-spinner spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    <span id="login-submit-text">Acessar conta</span>
                                </button>

                                <div class="alert d-none mt-3 mb-0" id="login-message" role="status" aria-live="polite"></div>
                            </form>

                            <p class="text-secondary text-center mt-4 mb-0">Ainda nao tem conta? <a class="link-primary fw-semibold text-decoration-none" href="#">Solicite acesso</a></p>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/login.js"></script>
</body>
</html>
