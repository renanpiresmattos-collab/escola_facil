<?= view('components/header', [
    'pageTitle' => $pageTitle,
    'activeMenu' => $activeMenu,
    'activeSubmenu' => $activeSubmenu,
]) ?>

<section class="content-card" data-rup-list-page>
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1">Registro Único de Pessoas</h2>
        </div>
        <a class="btn btn-primary" href="<?= site_url('rup/create') ?>">Incluir</a>
    </div>

    <form class="row g-3 mb-4" id="rup-filter-form">
        <div class="col-12 col-md-3">
            <label class="form-label" for="filter-field">Filtrar por</label>
            <select class="form-select" id="filter-field" name="field">
                <option value="cpf">CPF</option>
                <option value="nome">Nome</option>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="filter-q">Pesquisar</label>
            <input class="form-control" id="filter-q" name="q" placeholder="Digite o CPF ou nome">
        </div>
        <div class="col-12 col-md-3 d-flex align-items-end">
            <button class="btn btn-outline-secondary w-100" type="submit">Buscar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="table-light">
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="rup-list-body">
                <tr>
                    <td colspan="5" class="text-center text-secondary py-4">Carregando registros...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
        <small class="text-secondary" id="rup-pagination-summary"></small>
        <nav aria-label="Paginação da RUP">
            <ul class="pagination mb-0" id="rup-pagination"></ul>
        </nav>
    </div>
</section>

<script>
window.rupApi = {
    list: '<?= site_url('api/rup') ?>',
    editBase: '<?= site_url('rup') ?>',
    perPage: 10
};
</script>
<script src="<?= base_url('assets/js/rup.js') ?>"></script>

<?= view('components/footer') ?>
