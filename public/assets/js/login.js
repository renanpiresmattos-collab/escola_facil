const loginForm = document.getElementById('login-form');
const emailInput = document.getElementById('login-email');
const passwordInput = document.getElementById('login-password');
const passwordToggle = document.getElementById('password-toggle');
const submitButton = document.getElementById('login-submit');
const submitText = document.getElementById('login-submit-text');
const loginMessage = document.getElementById('login-message');

function setMessage(type, text) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

    loginMessage.textContent = text;
    loginMessage.className = `alert ${alertClass} mt-3 mb-0`;
}

function clearMessage() {
    loginMessage.textContent = '';
    loginMessage.className = 'alert d-none mt-3 mb-0';
}

function setLoading(isLoading) {
    submitButton.disabled = isLoading;
    submitButton.classList.toggle('is-loading', isLoading);
    submitText.textContent = isLoading ? 'Entrando...' : 'Acessar conta';
}

passwordToggle.addEventListener('click', () => {
    const shouldShowPassword = passwordInput.type === 'password';

    passwordInput.type = shouldShowPassword ? 'text' : 'password';
    passwordToggle.setAttribute('aria-label', shouldShowPassword ? 'Ocultar senha' : 'Mostrar senha');
    passwordInput.focus();
});

loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearMessage();

    const email = emailInput.value.trim();
    const password = passwordInput.value;

    if (!email || !password) {
        setMessage('error', 'Informe email e senha para continuar.');
        return;
    }

    setLoading(true);

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password }),
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            setMessage('error', data.message || 'Nao foi possivel realizar login.');
            return;
        }

        if (data.access_token) {
            localStorage.setItem('access_token', data.access_token);
        }

        setMessage('success', 'Login realizado com sucesso.');
    } catch (error) {
        setMessage('error', 'Falha de conexao. Tente novamente em instantes.');
    } finally {
        setLoading(false);
    }
});
