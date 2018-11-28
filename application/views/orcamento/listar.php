<main>
    <h3 class="title">Orçamentos</h2>

    <div class="row categorias-list">
        <table class="bordered responsive-table">
            <thead>
                <th>Data realizado</th>
                <th>Cliente</th>
                <th>Serviço</th>
                <th>Finalizado</th>
                <th>Ações</th>
            </thead>
            <tbody>
                <?php foreach ($orcamentos as $key => $orcamento) { ?>
                    <tr class="<?= (!$orcamento->orcamento_visualizado) ? "bold" : "" ?>">
                        <td class="center-align"><?= date('d/m/Y', strtotime($orcamento->data_realizado_orcamento)) ?></td>
                        <td class="left-align"><?= $orcamento->nome_usuario_realizou_orcamento ?></td>
                        <td class="center-align"><?= $orcamento->titulo_servico ?></td>
                        <td class="center-align"><?= ($orcamento->orcamento_finalizado) ? "<i class=\"material-icons blue-text\">check</i>" : "" ?></td>
                        <td class="center-align">
                            <a href="<?= base_url("orcamento/visualizar/$orcamento->id_orcamento") ?>" title="visualizar">
                                <i class="material-icons">search</i>
                            </a>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</main>