<main>
    <h3 class="title">Serviços</h2>

    <?php if(!empty($this->base->getPerfil())){?>
    <a href="<?= base_url('servicos/cadastrar')?>" class="btn blue white-text">Cadastre um novo serviço</a>

    <div class="row">
        <table class="bordered responsive-table">
            <thead>
                <th>Título</th>
                <th>Descrição</th>
                <th class="text-center">Nº de visualizações</th>
                <th class="text-center">Ações</th>
            </thead>
            <tbody>
                <?php foreach($servicos as $index => $servico){?>
                    <tr>
                        <td style="width: 20rem"><?= $servico->titulo_servico ?></td>
                        <td style="max-width: 25rem" class="text-ellipsis"><?= str_replace("<br />", "", $servico->descricao_servico) ?></td>
                        <td class="text-center"><?= $servico->numero_visualizacoes ?></td>
                        <td class="text-center">
                            
                            <a href="<?= base_url("servicos/detalhes/$servico->id_servico")?>"><i class="material-icons">search</i></a>
                            <a href="<?= base_url("servicos/editar/$servico->id_servico")?>"><i class="material-icons">edit</i></a>
                            <a href="#modal-excluir-<?= $index?>" class="modal-trigger"><i class="material-icons">delete</i></a></td>

                    </tr>

                    <div id="modal-excluir-<?= $index?>" class="modal">
                        <div class="modal-content">
                            <h5>Você realmente deseja excluir o serviço <b><?= $servico->titulo_servico?></b></h5>

                            <br>

                            <a class="btn blue white-text" href="<?= base_url("servicos/excluir/$servico->id_servico")?>">Excluir</a>
                            <a class="btn btn-grey grey-text modal-close">Cancelar</a>
                        </div>
                    </div>
                <?php }?>
            </tbody>
        </table>
    </div>
    <?php } else{?>
        <div class="nothing">
            <p>Opa!. <i class="material-icons">sentiment_very_dissatisfied</i></p> 
            
            <p>Não identificamos o seu perfil cadastrado.</p>

            <p>Para criar um serviço é necessário completar o seu perfil.</p>

            <a href="<?= base_url("perfil/editar") ?>" class="btn blue white-text">Editar perfil</a>
        </div>
    <?php } ?>
</main>