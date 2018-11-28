<main>
    <h3 class="title">Serviços denunciados</h2>

    <div class="row">
        <table class="bordered responsive-table">
            <thead>
                <th>Ativo</th>
                <th>Prestador de serviço</th>
                <th>Título</th>
                <th>Descrição</th>
                <th class="text-center">Nº de views</th>
                <th class="text-center">Nº de denuncias</th>
                <th class="text-center">Ações</th>
            </thead>
            <tbody>
                <?php foreach($servicos as $index => $servico){?>
                    <tr>
                        <td class="center-align"><?= ($servico->inativo) ? "<span class=\"red-text\"><i class=\"material-icons\">block</span>" : "<span class=\"blue-text\"><i class=\"material-icons\">check</span>" ?></td>
                        <td><?= $servico->nome_usuario ?></td>
                        <td style="min-width: 20rem"><?= $servico->titulo_servico ?></td>
                        <td style="max-width: 25rem" class="text-ellipsis"><?= str_replace("<br />", "", $servico->descricao_servico) ?></td>
                        <td class="text-center"><?= $servico->numero_visualizacoes ?></td>
                        <td class="text-center"><?= $servico->qtd_denuncias ?></td>
                        <td class="text-center"><a href="<?= base_url("admin/denuncias/servico/$servico->id_servico")?>"><i class="material-icons">search</i></a></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</main>