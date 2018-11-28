<main>
    <h3 class="title">Categorias</h2>

    <div class="row categorias-list">
        <?php 
            foreach ($categorias as $key => $categoria) { ?>

                <a href="<?= base_url("servicos/exibir/$categoria->id_categoria_servico") ?>" class="item col s4 m2" title="<?= $categoria->nome_categoria_servico ?>">
                    <center>
                        <p>
                            <i class="material-icons"><?= $categoria->icon_name ?></i>
                        </p>

                        <p class="text-ellipsis">
                            <?= $categoria->nome_categoria_servico ?>
                        </p>
                    </center>
                </a>

            <?php }
        ?>
    </div>
</main>