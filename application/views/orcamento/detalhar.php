<main>
    <h3 class="title">Orçamento (<?= $orcamento->titulo_servico?> / <?= $orcamento->id_orcamento ?>)</h2>

    <a href="<?= base_url("servicos/detalhes/$orcamento->id_servico") ?>" target="_blank" class="btn blue white-text <?= ($this->servico->isServicoInativo($orcamento->id_servico) ? "disabled" : "") ?>">Visualizar serviço</a>

    <a href="#modal-mensagem" class="btn green white-text modal-trigger"><i class="material-icons left">chat_bubble_outline</i> Mensagens <?= ($existe_mensagem_nao_visualizada) ? "<span class=\"new badge red notification\" data-badge-caption=\"\"></span>" : ""?></a>

    <?php if($orcamento->orcamento_finalizado && $this->base->isCliente() && empty($orcamento->nota->nota)){?>
    <div class="row">
        <br>
    
        <a href="#modal-avaliar" class="btn blue white-text modal-trigger"><i class="material-icons white-text left">favorite</i>Avalie esse serviço</a>

        <div class="modal" id="modal-avaliar">
            <div class="modal-content center-align">
                <h5 class="blue-text">Qual a nota para o serviço prestado?</h5>

                <br>

                <div class="nota star-action">
                    <a href=""><i class="material-icons blue-text" data-nota="1">star_border</i></a>
                    <a href=""><i class="material-icons blue-text" data-nota="2">star_border</i></a>
                    <a href=""><i class="material-icons blue-text" data-nota="3">star_border</i></a>
                    <a href=""><i class="material-icons blue-text" data-nota="4">star_border</i></a>
                    <a href=""><i class="material-icons blue-text" data-nota="5">star_border</i></a>
                </div>

                <div class="nota-final">
                    <h1 class="blue-text text-in">NOTA</h1>
                </div>


                <form action="<?= base_url("servico/avaliar/$orcamento->id_orcamento")?>" method="POST">
                    <input type="hidden" name="nota" id="form_hidden_nota">
                    <input type="hidden" name="id_servico" value="<?= $orcamento->id_servico ?>">
                    <input type="submit" value="Avaliar" id="form_submit_nota" class="btn blue-white-text disabled">
                </form>
            </div>
        </div>
    </div>
    <?php }?>

    <?php if(!empty($orcamento->nota->nota)){?>
        <div class="row">
            <div class="col s12"><p>Você já avaliou esse serviço.</p></div>
            <div class="col s12"><h6>Nota data foi: <span class="blue-text"><?= $orcamento->nota->nota ?></span></h6></div>
        </div>
        
    <?php }?>

    <div class="modal" id="modal-mensagem" <?= !empty($this->session->flashdata('mensagem_enviada')) ? "open=\"true\"" : "" ?>>
        <div class="modal-content">
            <h4 class="title">Mensagens</h4>

            <div class="mensagens">
                <?php if(!empty($mensagens)){?>
                    <?php foreach ($mensagens as $key => $mensagem) {?>
                        <div class="chat chat-<?= ($mensagem->mensagem_enviada_pelo_profissional) ? "right" : "left" ?>">
                            <small class="data <?= ($mensagem->mensagem_enviada_pelo_profissional) ? "left" : "right" ?>"><?= date('d/m/Y  \à\s H:i', strtotime($mensagem->data_envio)) ?></small>
                            <small class="<?= ($mensagem->mensagem_enviada_pelo_profissional) ? "white" : "blue" ?>-text"><?= ($mensagem->mensagem_enviada_pelo_profissional) ? "Prestador de serviço" : $orcamento->nome_usuario_realizou_orcamento ?></small>
                            <p><?= $mensagem->mensagem ?></p>
                        </div>        
                    <?php }?>
                <?php } else{?>    
                    <p>Ainda não há mensagens nesse orçamento.</p>
                <?php } ?>    
            </div>
        </div>

        <div class="modal-footer">
            <form action="<?= base_url("orcamento/enviar_mensagem/$orcamento->id_orcamento") ?>" method="post">
                <div class="row">
                    <div class="input-field col s12 m10">
                        <input name="form-chat" id="form-chat" type="text" class="validate" required>
                        <label for="form-chat">Adicionar nova mensagem</label>
                    </div>
                    <div class="input-field col right no-mobile">
                        <input type="submit" value="enviar" class="btn">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <fieldset class="line">
        <legend>Detalhes</legend>

        <div class="content">
            <p><b>Data orçamento:</b> <?= date('d/m/Y', strtotime($orcamento->data_realizado_orcamento)) ?></p>
            
            <div class="col s12">
                <p><b>Necessidade:</b>
                    <?= $orcamento->necessidade ?>
                </p>
            </div>

            <div class="col s12">
               <p><b>Prazo:</b> <?= $orcamento->prazo ?></p>
            </div>

            <div class="col s12">
                <p><b>Nome cliente:</b> <?= $orcamento->nome_usuario_realizou_orcamento ?></p>

                <p><b>E-mail:</b> <?= $orcamento->email_usuario_realizou_orcamento ?></p>

                <p><b>Telefone:</b> <?= $orcamento->telefone_usuario_realizou_orcamento ?></p>

                <p><b>Cidade:</b> <?= "$orcamento->cidade - $orcamento->uf" ?></p>

                <p><b>Endereço:</b> <?= $orcamento->endereco_usuario_realizou_orcamento ?></p>

                <p><b>CEP:</b> <?= $orcamento->cep_usuario_realizou_orcamento ?></p>
            </div>

        </div>
    </fieldset>

    <div class="row">
        <div class="col s12">
            <?= ($orcamento->orcamento_finalizado) ? "Esse orçamento já foi finalizado" : "Esse orçamento ainda não foi finalizado" ?>
        </div>
    </div>

    <?php if(!$orcamento->orcamento_finalizado && $this->base->isProfissional()) {?>
    <div class="row">
        <div class="col s12">
            <a href="#modal-finalizar-orcamento" class="btn blue white-text modal-trigger">Finalizar orçamento</a>
        </div>
    </div>

    <div class="modal" id="modal-finalizar-orcamento">
        <div class="modal-content">
            <h5 class="center-align">Você confirma em finalizar o orçamento (<?= $orcamento->titulo_servico?> / <?= $orcamento->id_orcamento ?>)?</h5>
        </div>
        <div class="modal-footer" style="text-align: center !important; margin-bottom: 1rem">
            <a href="#" class="btn btn-large modal-close grey white-text">Não</a>
            <a href="<?= base_url("orcamento/finalizar/$orcamento->id_orcamento") ?>" class="btn btn-large blue white-text">Sim</a>
        </div>
    </div>
    <?php }?>
</main>


<script>
    var stars = new Array();

    var notaFinal = 0;

    $('.star-action a i').mouseenter(function(e){
        var star = $(e.target);

        var nota = star.data('nota');

        for(let i = 1; i <= nota; i++){
            star_temp = $(`.star-action a i[data-nota="${i}"]`);

            stars[i] = star_temp.text();

            star_temp.text('star');
        }
       
    });

    $('.star-action a i').mouseleave(function(e){
        var star = $(e.target);

        var nota = star.data('nota');

        for(let i = 1; i <= nota; i++){
            if(i > notaFinal)
            {
                star_temp = $(`.star-action a i[data-nota="${i}"]`);
                star_temp.text('star_border');
            }
        }
    });

    $('.star-action a i').click(function(e){
        e.preventDefault();

        $('.star-action a i').text('star_border');

        var star = $(e.target);

        var nota = star.data('nota');

        for(let i = 1; i <= nota; i++){
            star_temp = $(`.star-action a i[data-nota="${i}"]`);

            stars[i] = star_temp.text();

            star_temp.text('star');
        }

        $('.nota-final .text-in').text(nota);

        notaFinal = nota;

        $('#form_hidden_nota').val(nota);

        $('#form_submit_nota').removeClass('disabled')
    });
</script>