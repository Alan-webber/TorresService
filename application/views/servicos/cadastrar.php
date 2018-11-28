<?php 
    if(!empty($servico))
    {
        $formAction = base_url("servicos/editar/$servico->id_servico");
        $titulo = "Editar serviço";
        $formSubmitValue = "Editar";
        $edit = true;
    }
    else
    {
        $formAction = base_url("servicos/cadastrar");
        $titulo = "Cadastro de serviço";
        $formSubmitValue = "Cadastrar";
        $edit = false;
    }
?>

<main>
    <?php if(!empty($this->base->getPerfil())){?>
    <h3 class="title"><?= $titulo?></h2>

    <div class="row">
        <form action="<?= $formAction?>" method="POST" class="col s12" enctype="multipart/form-data">
        <div class="row">
                <div class="input-field col s12">
                    <input name="titulo_servico" id="titulo_servico" type="text" class="validate" value="<?= (($edit == true) ? $servico->titulo_servico : "") ?>" required>
                    <label for="titulo_servico">Título do serviço</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <textarea name="descricao_servico" id="descricao_servico" class="validate materialize-textarea" required row="10"><?= (($edit == true) ? str_replace("<br />", "", $servico->descricao_servico) : "") ?></textarea>
                    <label for="descricao_servico">Descrição do serviço</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m4">
                    <select name="categoria_servico" id="categoria_servico" required>
                        <option value="" disabled<?= (($edit == true) ? "selected" : "") ?>>Selecione sua categoria</option>
                        <?php foreach($categorias as $categoria){?>
                            <option 
                                value="<?= $categoria->id_categoria_servico?>" 
                                <?= (($edit == true && $servico->id_categoria_servico == $categoria->id_categoria_servico) ? "selected" : "") ?>
                                ><?= $categoria->nome_categoria_servico?></option>
                        <?php }?>
                    </select>
                    <label for="categoria_servico">Categoria do serviço</label>
                </div>
                <div class="input-field col s12 m4">
                    <select name="dias_semana[]" id="dias_semana" multiple required>
                        <option value="1" <?= ($edit == true && in_array(1, $servico->dias_semana) ? "selected" : "") ?>>Domingo</option>
                        <option value="2" <?= ($edit == true && in_array(2, $servico->dias_semana) ? "selected" : "") ?>>Segunda-feira</option>
                        <option value="3" <?= ($edit == true && in_array(3, $servico->dias_semana) ? "selected" : "") ?>>Terça-feira</option>
                        <option value="4" <?= ($edit == true && in_array(4, $servico->dias_semana) ? "selected" : "") ?>>Quarta-feira</option>
                        <option value="5" <?= ($edit == true && in_array(5, $servico->dias_semana) ? "selected" : "") ?>>Quinta-feira</option>
                        <option value="6" <?= ($edit == true && in_array(6, $servico->dias_semana) ? "selected" : "") ?>>Sexta-feira</option>
                        <option value="7" <?= ($edit == true && in_array(7, $servico->dias_semana) ? "selected" : "") ?>>Sábado</option>
                    </select>
                    <label for="dias_semana">Dias da semana atendidos</label>
                </div>
                <div class="input-field col s5 m2">
                    <input type="text" name="hora_inicial" id="hora_inicial" class="timepicker" placeholder="Ex.: 08:30" required value="<?= (($edit == true) ? $servico->hora_inicial : "") ?>">
                    <label for="hora_inicial">Horário de início</label>
                </div>
                <div class="input-field col s5 m2">
                    <input type="text" name="hora_final" id="hora_final" class="timepicker" placeholder="Ex.: 18:30" required value="<?= (($edit == true) ? $servico->hora_final : "") ?>">
                    <label for="hora_final">Horário final</label>
                </div>
            </div>

            
            <?php if($edit == true && !empty($fotos)){?>
                <div class="row">
                    <div class="col s12">
                        Fotos adicionadas:
                    </div>
                </div>

                <input type="hidden" name="fotos-excluir" id="fotos-excluir">

                <div class="row">
                    <?php foreach($fotos as $i => $foto){?>
                        <div class="col foto-item" id="<?= "foto-{$foto['id_servico_fotos']}" ?>">
                            <span class="delete" data-id="<?= $foto['id_servico_fotos']?>"><i class="material-icons">close</i></span>
                            <img src="<?= base_url($foto['url'])?>" alt="">
                        </div>
                    <?php } ?>
                </div>
            <?php }?>

            <div class="row">
                <div class="col s12">
                    Fotos:
                </div>
            </div>

            <div class="row">
                <div class="col s12 upload-file">
                    <div class="fileSpace">
                        <div class="file-field input-field input-file-0">
                            <div class="onlyButton" id="onlyButton0">
                                <span class="delete" onclick="removeButton(0)"><i class="material-icons">close</i></span>
                                <span class="number">1</span>
                                <span class="add"><i class="material-icons" style="font-size: 1.5em">add</i></span>
                                <input type="file" class="file" name="fotos[]" id="file-0">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" id="file-path-0" onchange="addButton('.fileSpace', 0)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <input type="submit" value="<?= $formSubmitValue?>" class="btn blue white-text">
                </div>
            </div>
        </form>
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


<script>
    var fotos_excluir = new Array();

    var input = $('#fotos-excluir');

    $('.foto-item .delete').click(function(e){
        e.preventDefault();

        var id_foto = $(this).attr('data-id');

        var elem = $("#foto-"+id_foto);

        if($(elem).hasClass('deleted'))
        {
            var novo_array = Array();

            fotos_excluir.forEach(element => {
                if(element != id_foto)
                {
                    novo_array.push(element);
                }    
            });

            fotos_excluir = novo_array;

            $(elem).removeClass('deleted');
        }
        else
        {
            fotos_excluir.push(id_foto); 

            $(elem).addClass('deleted');
        }

        atualizar_input();
    });

    var atualizar_input = function(){
        input.val(fotos_excluir.join(';'));
    }
</script>