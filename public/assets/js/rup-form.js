document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('[data-rup-form-page]');
    if (!page || !window.rupApi) {
        return;
    }

    const mode = page.dataset.mode;
    const recordId = page.dataset.recordId;
    const form = document.getElementById('rup-form');
    const message = document.getElementById('rup-form-message');
    const saveButton = document.getElementById('rup-save-btn');

    const cpf = document.getElementById('cpf');
    const telefone = document.getElementById('telefone');
    const cep = document.getElementById('cep');

    function setMessage(type, text) {
        message.className = `alert alert-${type} mt-4 mb-0`;
        message.textContent = text;
    }

    function clearMessage() {
        message.className = 'alert d-none mt-4 mb-0';
        message.textContent = '';
    }

    function digits(value) {
        return String(value || '').replace(/\D+/g, '');
    }

    function maskCpf(value) {
        const v = digits(value).slice(0, 11);
        return v
            .replace(/^(\d{3})(\d)/, '$1.$2')
            .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
            .replace(/\.(\d{3})(\d)/, '.$1-$2');
    }

    function maskPhone(value) {
        const v = digits(value).slice(0, 11);
        if (v.length <= 10) {
            return v.replace(/^(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d)/, '$1-$2');
        }
        return v.replace(/^(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
    }

    function maskCep(value) {
        const v = digits(value).slice(0, 8);
        return v.replace(/^(\d{5})(\d)/, '$1-$2');
    }

    function setLoading(loading) {
        saveButton.disabled = loading;
        saveButton.textContent = loading ? 'Salvando...' : 'Salvar';
    }

    async function loadRecord() {
        if (mode !== 'edit' || !recordId) {
            cpf.disabled = false;
            return;
        }

        const response = await fetch(`${window.rupApi.showBase}/${recordId}`, {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        });

        const json = await response.json().catch(() => ({}));
        if (!response.ok) {
            setMessage('danger', json.message || 'Não foi possível carregar o registro.');
            return;
        }

        const record = json.data || {};
        cpf.value = maskCpf(record.cpf || '');
        cpf.disabled = true;
        document.querySelector('input[name="cpf"][type="hidden"]')?.remove();
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'cpf';
        hidden.value = digits(record.cpf || '');
        form.appendChild(hidden);

        form.nome.value = record.nome || '';
        form.data_nascimento.value = record.data_nascimento || '';
        form.pai.value = record.pai || '';
        form.mae.value = record.mae || '';
        form.email.value = record.email || '';
        form.telefone.value = maskPhone(record.telefone || '');
        form.endereco.value = record.endereco || '';
        form.bairro.value = record.bairro || '';
        form.cidade.value = record.cidade || '';
        form.uf.value = record.uf || '';
        form.cep.value = maskCep(record.cep || '');
        form.observacoes.value = record.observacoes || '';
    }

    cpf.addEventListener('input', function () {
        cpf.value = maskCpf(cpf.value);
    });
    telefone.addEventListener('input', function () {
        telefone.value = maskPhone(telefone.value);
    });
    cep.addEventListener('input', function () {
        cep.value = maskCep(cep.value);
    });

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        clearMessage();
        setLoading(true);

        const payload = {
            cpf: digits(cpf.value),
            nome: form.nome.value.trim(),
            data_nascimento: form.data_nascimento.value,
            pai: form.pai.value.trim(),
            mae: form.mae.value.trim(),
            email: form.email.value.trim(),
            telefone: digits(form.telefone.value),
            endereco: form.endereco.value.trim(),
            bairro: form.bairro.value.trim(),
            cidade: form.cidade.value.trim(),
            uf: form.uf.value.trim(),
            cep: digits(form.cep.value),
            observacoes: form.observacoes.value.trim(),
        };

        try {
            const response = await fetch(mode === 'edit' ? `${window.rupApi.updateBase}/${recordId}` : window.rupApi.create, {
                method: mode === 'edit' ? 'PUT' : 'POST',
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const json = await response.json().catch(() => ({}));
            if (!response.ok) {
                setMessage('danger', json.message || 'Não foi possível salvar.');
                return;
            }

            setMessage('success', json.message || 'Registro salvo com sucesso.');
            window.setTimeout(function () {
                window.location.href = '/rup';
            }, 800);
        } catch (error) {
            setMessage('danger', 'Falha de conexão. Tente novamente.');
        } finally {
            setLoading(false);
        }
    });

    loadRecord();
});
