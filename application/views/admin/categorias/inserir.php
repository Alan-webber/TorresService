<main>
    <div class="row">
        <h3 class="title">Cadastro de categorias</h2>

        <form action="<?= base_url('admin/cadastrar_categoria') ?>" method="POST">
            <div class="input-field col s12 m10">
                <input name="nome_categoria_servico" id="nome_categoria_servico" type="text" class="validate" value="" required>
                <label for="nome_categoria_servico">Nome da categoria</label>
            </div>
    
            <div class="input-field col s12 m2">
                <input type="submit" class="btn btn-border" value="Cadastrar categoria" disabled>
            </div>

            <input type="hidden" name="icon_name">
        </form>

        <div class="col s12">
            <p>Selecione o ícone abaixo: </p>
            <div class="icones-categorias">
                <?php foreach ($icones as $categoria) { ?>
                    <p class="categoria-nome"><?= $categoria['name']?></p>
        
                    <?php foreach ($categoria['icons'] as $icone) { ?>
                        <a href="" class="icone" data-icone="<?= $icone['id'] ?>">
                            <i class="material-icons"><?= $icone['id']?></i>
                            <span><?= $icone['id']?></span>
                        </a>
                    <?php }?>
                <?php }?>
            </div>
        </div>

    </div>
    

</main>