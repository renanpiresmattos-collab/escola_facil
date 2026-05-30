document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('[data-rup-list-page]');
    if (!page || !window.rupApi) {
        return;
    }

    const form = document.getElementById('rup-filter-form');
    const field = document.getElementById('filter-field');
    const query = document.getElementById('filter-q');
    const tbody = document.getElementById('rup-list-body');
    const pager = document.getElementById('rup-pagination');
    const summary = document.getElementById('rup-pagination-summary');

    const params = new URLSearchParams(window.location.search);
    field.value = params.get('field') || 'cpf';
    query.value = params.get('q') || '';

    function currentPage() {
        return Math.max(1, parseInt(new URLSearchParams(window.location.search).get('page') || '1', 10));
    }

    function formatCpf(value) {
        const digits = String(value || '').replace(/\D+/g, '').slice(0, 11);
        return digits.replace(/^(\d{3})(\d)/, '$1.$2').replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3').replace(/\.(\d{3})(\d)/, '.$1-$2');
    }

    function formatPhone(value) {
        const digits = String(value || '').replace(/\D+/g, '').slice(0, 11);
        if (!digits) return '';
        if (digits.length <= 10) {
            return digits.replace(/^(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d)/, '$1-$2');
        }
        return digits.replace(/^(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
    }

    function renderRows(items) {
        if (!items.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-secondary py-4">Nenhum registro encontrado.</td></tr>';
            return;
        }

        tbody.innerHTML = items.map(function (item) {
            return `
                <tr>
                    <td>${escapeHtml(formatCpf(item.cpf))}</td>
                    <td>${escapeHtml(item.nome || '')}</td>
                    <td>${escapeHtml(item.email || '')}</td>
                    <td>${escapeHtml(formatPhone(item.telefone || ''))}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="${window.rupApi.editBase}/${item.id}/edit">Editar</a>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function pageButton(label, pageNumber, disabled, active) {
        if (disabled) {
            return `<li class="page-item disabled"><span class="page-link">${label}</span></li>`;
        }

        return `<li class="page-item ${active ? 'active' : ''}"><button class="page-link" type="button" data-page="${pageNumber}">${label}</button></li>`;
    }

    function renderPagination(meta) {
        const pageNumber = Number(meta.page || 1);
        const lastPage = Number(meta.last_page || 1);
        const total = Number(meta.total || 0);
        const perPage = Number(meta.per_page || window.rupApi.perPage || 10);

        if (summary) {
            summary.textContent = total > 0
                ? `Mostrando ${Math.min((pageNumber - 1) * perPage + 1, total)} a ${Math.min(pageNumber * perPage, total)} de ${total} registros`
                : 'Nenhum registro encontrado.';
        }

        if (!pager) {
            return;
        }

        if (lastPage <= 1) {
            pager.innerHTML = '';
            return;
        }

        const parts = [];
        parts.push(pageButton('Anterior', pageNumber - 1, pageNumber <= 1, false));

        const windowSize = 5;
        let start = Math.max(1, pageNumber - Math.floor(windowSize / 2));
        let end = Math.min(lastPage, start + windowSize - 1);
        start = Math.max(1, end - windowSize + 1);

        if (start > 1) {
            parts.push(pageButton('1', 1, false, pageNumber === 1));
            if (start > 2) {
                parts.push('<li class="page-item disabled"><span class="page-link">…</span></li>');
            }
        }

        for (let i = start; i <= end; i++) {
            parts.push(pageButton(String(i), i, false, pageNumber === i));
        }

        if (end < lastPage) {
            if (end < lastPage - 1) {
                parts.push('<li class="page-item disabled"><span class="page-link">…</span></li>');
            }
            parts.push(pageButton(String(lastPage), lastPage, false, pageNumber === lastPage));
        }

        parts.push(pageButton('Próxima', pageNumber + 1, pageNumber >= lastPage, false));
        pager.innerHTML = parts.join('');

        pager.querySelectorAll('[data-page]').forEach(function (button) {
            button.addEventListener('click', function () {
                const next = new URL(window.location.href);
                next.searchParams.set('field', field.value || 'cpf');
                if (query.value.trim()) {
                    next.searchParams.set('q', query.value.trim());
                } else {
                    next.searchParams.delete('q');
                }
                next.searchParams.set('page', this.dataset.page);
                window.location.href = next.toString();
            });
        });
    }

    async function load() {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-secondary py-4">Carregando registros...</td></tr>';

        const url = new URL(window.rupApi.list, window.location.origin);
        url.searchParams.set('field', field.value || 'cpf');
        url.searchParams.set('page', currentPage());
        url.searchParams.set('per_page', window.rupApi.perPage || 10);
        if (query.value.trim()) {
            url.searchParams.set('q', query.value.trim());
        }

        const response = await fetch(url.toString(), {
            credentials: 'same-origin',
            headers: { Accept: 'application/json' },
        });

        const json = await response.json().catch(() => ({}));
        if (!response.ok) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">${escapeHtml(json.message || 'Falha ao carregar registros.')}</td></tr>`;
            if (summary) summary.textContent = '';
            if (pager) pager.innerHTML = '';
            return;
        }

        renderRows(Array.isArray(json.data) ? json.data : []);
        renderPagination(json.meta || {});
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const next = new URL(window.location.href);
        next.searchParams.set('field', field.value || 'cpf');
        if (query.value.trim()) {
            next.searchParams.set('q', query.value.trim());
        } else {
            next.searchParams.delete('q');
        }
        next.searchParams.delete('page');
        window.location.href = next.toString();
    });

    load();
});

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}
