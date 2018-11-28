<main>
    <h3 class="title">Categorias</h2>

    <a href="<?= base_url('admin/inserir_categoria')?>" class="btn blue white-text">Cadastre uma nova categoria</a>

    <div class="row">
        <table class="bordered responsive-table">
            <thead>
                <th>Categoria</th>
                <th>Nº de anuncios na categoria</th>
                <th>Ações</th>
            </thead>
            <tbody>
                <?php foreach($categorias as $index => $categoria){?>
                    <tr>
                        <td><i class="material-icons blue-text" style="margin-right: 1rem"><?= $categoria->icon_name?></i> <?= $categoria->nome_categoria_servico ?></td>
                        <td class="text-center"><?= $categoria->numero_anuncios ?></td>
                        <td class="text-center"><a href="#modal-excluir-<?= $index?>" class="modal-trigger">Excluir</a></td>
                    </tr>

                    <div id="modal-excluir-<?= $index?>" class="modal">
                        <div class="modal-content">
                            <h5>Você realmente deseja excluir a categoria <b><?= $categoria->nome_categoria_servico?></b></h5>

                            <br>

                            <a class="btn blue white-text" href="<?= base_url("admin/excluir_categoria/$categoria->id_categoria_servico")?>">Excluir</a>
                            <a class="btn btn-grey grey-text modal-close">Cancelar</a>
                        </div>
                    </div>
                <?php }?>
            </tbody>
        </table>
    </div>
</main>