<main>
    <div class="row">
        <?php if(!empty($servicos) && count($servicos) > 0){?>
        <div class="col s12 <?= !empty($mais_visitados) ? "m8" : "" ?>">

        <?php if(!empty($categoria)){?>
        <h3 class="title">Serviços de <?= $categoria->nome_categoria_servico?></h2>
        <?php }?>
        
        <?php if(!empty($favoritos)){?>
        <h3 class="title">Favoritos</h2>
        <?php }?>

        <?php if(!empty($busca)){?>
        <h3 class="title">Buscando por <?= $busca?></h2>
        <?php }?>
        
        <?php if(!empty($titulo)){?>
        <h3 class="title"><?= $titulo?></h2>
        <?php }?>

        <div class="quantidade">Exibindo <?= (count($servicos) > 1) ? count($servicos)." resultados" : count($servicos)." resultado" ?></div>
        <?php foreach($servicos as $index => $servico){?>
        <a class="link-servico" href="<?= base_url("servicos/detalhes/$servico->id_servico")?>">
            <div class="card-servico <?= ($servico->inativo) ? 'border-red' : ''?>">
                <div class="foto">
                    <?php if(!empty($servico->foto_principal)){ ?>
                        <img src="<?= base_url($servico->foto_principal) ?>" alt="">
                    <?php }else{ ?>
                        <img src="" alt="">
                    <?php } ?>
                </div>
                
                <div class="descricao">
                    <div class="data"><?= date('d/m/Y \à\s H:i', strtotime($servico->data_servico))?></div>

                    <div class="titulo"><?= $servico->titulo_servico?></div>

                    <div class="favorito">
                        <span>
                            <i class="material-icons yellow-text text-darken-2"><?= ($servico->favorito) ? "star" : "star_border" ?></i>
                        </span>
                    </div>

                    <div class="prestador">
                        <?= $servico->nome_usuario?>
                            | 
                        <?= "desde: ". date('d/m/Y', strtotime($servico->data_cadastro)) ?>
                            | 
                        <?= $servico->numero_visualizacoes?> visualizações
                    </div>
                    
                </div>
            </div>
            
        </a>
        <?php }?>

        </div>

        <?php if( !empty($mais_visitados) ){?>

        <div class="col s12 m4">
            <h3 class="title">Mais visitados</h3>
            <div class="quantidade"> <br> </div>

            <?php  foreach ($mais_visitados as $key => $servico) { ?>
                <a href="<?= base_url("servicos/detalhes/$servico->id_servico")?>" class="link-servico">
                    <div class="card-servico">
                        <div class="descricao">
                            <div class="data"><?= date('d/m/Y \à\s H:i', strtotime($servico->data_servico))?></div>

                            <div class="titulo"><?= $servico->titulo_servico?></div>

                            <div class="prestador"><?= $servico->nome_usuario?> | <?= $servico->numero_visualizacoes?> visualizações</div>
                        </div>
                    </div>
                </a>
            <?php } ?>

        </div>

        <?php }?>

    <?php } else{?>
            <div class="nothing">
                <p>Opa!. <i class="material-icons">sentiment_very_dissatisfied</i></p> 
                
                <p>Não encontramos nenhum serviço por aqui.</p>

                <p>Procure em outras categorias para encontrar o serviço desejado.</p>

                <a href="<?= base_url("categorias") ?>" class="btn blue white-text">Categorias</a>
            </div>
        <?php }?>
    </div>
</main>