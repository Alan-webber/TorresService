<main>
    <h3 class="title">Denuncias</h2>

    <a href="<?= base_url("servicos/detalhes/$id_servico") ?>" class="btn blue white-text <?= ($servico_inativo) ? "disabled" :  "" ?> ">Ver serviço</a>

    <p class="grey-text">Exibindo <span class="blue-text"><?= count($denuncias) ?></span> denuncias.</p>

    <?php foreach ($denuncias as $key => $denuncia) {?>
        <div class="row">
            <fieldset>
                <legend><?= $denuncia->nome_usuario ?></legend>
                <small class="grey-text">Data denuncia: <?= date('d/m/Y H:m', strtotime($denuncia->data_hora_denuncia)) ?></small>


                <p>
                    <b>Motivo: </b>
                    <?= $denuncia->motivo_denuncia ?>
                </p>
            </fieldset>
        </div>
    <?php }?> 
</main>