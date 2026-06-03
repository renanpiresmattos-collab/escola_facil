<?= view('components/header', [
    'pageTitle' => $pageTitle,
    'activeMenu' => $activeMenu,
    'activeSubmenu' => $activeSubmenu,
]) ?>
<?php
$contextPrefix = in_array(service('request')->getUri()->getSegment(1), ['escolaum', 'escoladois'], true)
    ? service('request')->getUri()->getSegment(1)
    : '';
?>

<div class="container-fluid px-0 mb-3">
    <div class="alert d-none mb-0" id="rup-form-message" role="status" aria-live="polite"></div>
</div>

<section class="content-card" data-rup-form-page data-mode="<?= esc($mode) ?>" data-record-id="<?= esc($recordId ?? '') ?>">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
        <div>
            <h2 class="h4 mb-1" id="rup-form-title"><?= esc($pageTitle) ?></h2>
        </div>
        <a class="btn btn-outline-secondary" href="<?= site_url(($contextPrefix !== '' ? $contextPrefix . '/' : '') . 'rup') ?>">Voltar</a>
    </div>

    <form id="rup-form">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label class="form-label" for="cpf">CPF</label>
                <input class="form-control js-cpf-mask" id="cpf" name="cpf" required>
            </div>
            <div class="col-12 col-md-8">
                <label class="form-label" for="nome">Nome</label>
                <input class="form-control" id="nome" name="nome" required>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="data_nascimento">Data de nascimento</label>
                <input class="form-control" id="data_nascimento" name="data_nascimento" type="date">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="pai">Pai</label>
                <input class="form-control" id="pai" name="pai">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="mae">Mãe</label>
                <input class="form-control" id="mae" name="mae">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" id="email" name="email" type="email">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="telefone">Telefone</label>
                <input class="form-control js-phone-mask" id="telefone" name="telefone">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label" for="cep">CEP</label>
                <input class="form-control js-cep-mask" id="cep" name="cep">
            </div>
            <div class="col-10">
                <label class="form-label" for="endereco">Endereço</label>
                <input class="form-control" id="endereco" name="endereco">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="bairro">Bairro</label>
                <input class="form-control" id="bairro" name="bairro">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="cidade">Cidade</label>
                <input class="form-control" id="cidade" name="cidade">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label" for="uf">UF</label>
                <input class="form-control" id="uf" name="uf" maxlength="2">
            </div>
            <div class="col-12">
                <label class="form-label" for="observacoes">Mais informações</label>
                <textarea class="form-control" id="observacoes" name="observacoes" rows="4"></textarea>
            </div>
            <div class="col-12 d-flex flex-column flex-md-row gap-2 mt-2 justify-content-end">
                <button class="btn btn-primary" id="rup-save-btn" type="submit">Salvar</button>
            </div>
        </div>
    </form>
</section>

<script>
window.rupApi = {
    list: '<?= site_url(($contextPrefix !== '' ? $contextPrefix . '/' : '') . 'api/rup') ?>',
    showBase: '<?= site_url(($contextPrefix !== '' ? $contextPrefix . '/' : '') . 'api/rup') ?>',
    create: '<?= site_url(($contextPrefix !== '' ? $contextPrefix . '/' : '') . 'api/rup') ?>',
    updateBase: '<?= site_url(($contextPrefix !== '' ? $contextPrefix . '/' : '') . 'api/rup') ?>'
};
</script>
<script src="<?= base_url('assets/js/rup-form.js') ?>"></script>

<?= view('components/footer') ?>
