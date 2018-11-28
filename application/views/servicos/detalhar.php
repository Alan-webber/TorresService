<main>
    <br>
    <br>

    <div class="row">
        <div class="col s12 m4 push-m8">
            <div class="detalhes-profissional">
                <div class="profile">
                    <div class="foto">
                        <?php if($servico->foto_perfil){?>
                            <img src="<?= base_url($servico->foto_perfil)?>">
                        <?php }else{?>
                            <img src="https://marketplace.canva.com/MAB2sqFKHu0/1/thumbnail_large/canva-business-people-design-person-icon--MAB2sqFKHu0.png">
                        <?php }?>
                    </div>
                </div>

                <div class="detalhes-perfil-profissional">
                    <p class="nome">
                        <?= $servico->nome_usuario ?>
                    </p>


                    <p>Pessoa <?= ($servico->pessoa_fisica_juridica) ? 'fisica' : 'juridica'?></p>

                    <?= (!empty($servico->telefone1) ? "<p><b>Telefone:</b> {$servico->telefone1}</p>" : "") ?>
                    <?= (!empty($servico->telefone2) ? "<p><b>Telefone:</b> {$servico->telefone2}</p>" : "") ?>

                    <?php if($servico->pessoa_fisica_juridica){?>

                        <p><b>CPF: </b><?= $servico->cpf?></p>

                    <?php }else{?>

                        <p><b>CNPJ: </b><?= $servico->cnpj?></p>
                        <p><b>IE: </b><?= $servico->inscricao_estadual?></p>

                    <?php }?>

                    <p><b>Local: </b> <?= "$servico->cidade"?> </p>
                    
                    <?php if(!is_null($servico->profissao)) {?>
                        <p><b>Trabalha: </b> <?= $servico->profissao?> <?= (!is_null($servico->funcao) ? "- $servico->funcao" : "") ?></p>

                    <?php }?>

                    <small class="grey-text"><?= "Desde " . date('d/m/Y', strtotime($servico->data_cadastro)) ?></small>

                </div>
            </div>
        </div>
        <div class="col s12 m8 pull-m4">

            <div class="detalhes-servico z-depth-0">
                <div class="favorite">
                        <?php if($this->base->isAdmin()){?>
                            <!-- Dropdown Trigger -->
                            <a class='dropdown-trigger' href='#' data-target='menu-action-admin'><i class="material-icons blue-text">create</i></a>

                            <!-- Dropdown Structure -->
                            <ul id='menu-action-admin' class='dropdown-content'>
                                <?php if($servico->inativo){?>
                                    <li><a class="blue-text" href="<?= base_url("admin/ativar/servico/$servico->id_servico") ?>">Ativar</a></li>
                                <?php }else{?>
                                    <li><a class="blue-text" href="<?= base_url("admin/inativar/servico/$servico->id_servico") ?>">Inativar</a></li>
                                <?php }?>
                            </ul>
                        <?php }?>
                </div>

                <?= ($servico->inativo) ? "<span class=\"left new badge red\" data-badge-caption=\"inativo\"></span>" : "" ?>

                <h4><?= $servico->titulo_servico?></h4>
                <div class="data">Inserido em <?= date('d/m/Y \à\s H:i', strtotime($servico->data_servico))?></div>

                <!-- <div class="fb-share-button" data-href="<?= base_url($this->uri->uri_string()) ?>" data-layout="button_count"></div> -->
                
                <div class="fb-share-button" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartilhar</a></div>

                <?php if(!is_null($nota->media_nota)){?>
                    <p>Nota do serviço: <span class="blue-text"><?= $nota->media_nota ?></span></p>
                    <p>Avaliada por <span class="blue-text"><?= $nota->qtd ?></span> usuários.</p>
                <?php } else{ ?>
                    <p>Esse serviço ainda não foi avaliado.</p>
                <?php }?>

                <div class="detalhes-slider-fotos">
                    <?php if(!empty($servico->fotos)){?>
                    <button type="button" class="slick-prev">Prev</button>
                    <button type="button" class="slick-next slick-arrow">Next</button>

                    <div class="slider single-item">
                        <?php foreach ($servico->fotos as $key => $foto) {?>
                            <div><img class="img-slider" width="auto" src="<?= base_url($foto['url']) ?>"></div>
                        <?php } ?>
                    </div>
                    <?php } else{
                        echo "Serviço sem foto";
                    }?>
                </div>

                <?php if($this->session->userdata('id') != $servico->id_usuario && !$this->base->isAdmin()){?>
                <div class="row">
                    <div class="col s12 m6">
                        <a href="#modal-orcamento" class="btn blue white-text modal-trigger <?= !$pode_realizar_orcamento ? "disabled" : "" ?>">Solicite um orçamento</a>
                    </div>
                    <div class="col s12 m6 acoes">
                        <a title="Denunciar" href="#modal-denunciar" class="red-text text-darken-1 modal-trigger"><i class="material-icons">error</i></a>
                        <a title="Favoritar" class="yellow-text text-darken-3" href="<?= base_url("servicos/favoritar/{$servico->id_servico}")?>"><i class="material-icons"><?= ($servico->favorito) ? "star" : "star_border" ?></i></a>
                    </div>
                </div>

                <div class="modal" id="modal-orcamento">
                    <form action="<?= base_url("orcamento/novo_orcamento/$servico->id_servico") ?>" method="POST">
                        <div class="modal-content" style="height: 80vh !important">
                            <div class="row">
                                <div class="col"><h4 class="brand blue-text">Solicite um orçamento grátis</h4></div>

                                <div class="input-field col s12">
                                    <textarea name="necessidade" id="necessidade" class="validate materialize-textarea large-textarea" required></textarea>
                                    <label for="necessidade">Do que você precisa?</label>
                                </div>

                                <div class="input-field col s12">
                                    <select name="prazo" id="prazo" required>
                                        <option value="">Indique o prazo do serviço</option>
                                        <option value="O quanto antes">O quanto antes</option>
                                        <option value="Nesta semana">Nesta semana</option>
                                        <option value="Nas próximas semanas">Nas próximas semanas</option>
                                        <option value="Neste mês">Neste mês</option>
                                        <option value="Nos próximos meses">Nos próximos meses</option>
                                        <option value="Ainda não tenho previsão">Ainda não tenho previsão</option>
                                    </select>
                                    <label for="prazo">Para quando você precisa deste serviço?</label>
                                </div>

                                <div class="input-field col s12 m4">
                                    <input name="nome" id="nome" type="text" class="validate" value="<?= !empty($usuario->nome_usuario) ? $usuario->nome_usuario : "" ?>" required>
                                    <label for="nome">Nome completo</label>
                                </div>
                                <div class="input-field col s12 m4">
                                    <input name="email" id="email" type="email" class="validate" value="<?= !empty($usuario->email_usuario) ? $usuario->email_usuario : "" ?>" required>
                                    <label for="email">E-mail</label>
                                </div>
                                <div class="input-field col s12 m4">
                                    <input name="telefone" id="telefone" type="text" class="validate" value="<?= !empty($usuario->telefone1) ? $usuario->telefone1 : "" ?>" required>
                                    <label for="telefone">Telefone</label>
                                </div>

                                <div class="input-field col s12 m3">
                                    <input type="text" name="cidade" id="cidade" class="autocomplete" value="" placeholder="Cidade" required>
                                    <label for="cidade">Cidade</label>
                                </div>
                                <div class="input-field col s12 m7">
                                    <input name="endereco" id="endereco" type="text" class="validate" value="" placeholder="Endereço da realização do serviço" required>
                                    <label for="endereco">Endereço</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <input name="cep" id="cep" type="text" class="validate" value="" placeholder="CEP" required>
                                    <label for="cep">CEP</label>
                                </div>
                            </div>

                            
                        </div>
                        <div class="modal-footer">
                            <a href="" class="btn btn-grey grey-text modal-close">Cancelar</a>
                            <input type="submit" class="btn blue white-text" value="Enviar">
                        </div>
                    </form>
                </div>
                <?php }?>

                <p class="descricao">
                    <span class="blue-text">Descrição:</span>
                    <br>
                    <br>
                    <?= $servico->descricao_servico?>
                </p>

                <?= $servico->disponibilidade ?>

                <br>
                
                <div class="info">
                    <p class="categoria">
                        <small>Categoria: <b><?= $servico->nome_categoria_servico?></b></small>
                    </p>
                    <p class="codigoAnuncio">
                        <small>Código do anúncio: <b><?= $servico->id_servico?></b></small>
                    </p>

                    <p class="codigoAnuncio">
                        <small><?= $servico->numero_visualizacoes ?> visualizações</small>
                    </p>
                </div>
                
            </div>

        </div>
    </div>
</main>

<div class="modal" id="modal-denunciar">
    <form action="<?= base_url("servicos/denunciar/$servico->id_servico") ?>" method="POST">
        <div class="modal-content">
            <div class="row">
                <h5>Denunciar</h5>

                <div class="input-field col s12">
                    <textarea name="motivo_denuncia" id="motivo_denuncia" class="validate materialize-textarea" required row="10"></textarea>
                    <label for="motivo_denuncia">Motivo da denuncia</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="" class="btn btn-grey grey-text modal-close">Cancelar</a>
            <input type="submit" class="btn blue white-text" value="Denunciar">
        </div>
    </form>
</div>



<script>
    var cidades = <?= $cidades ?>

    $(document).ready(function(){
        var autocomplete_cidades = {};

        for( var i = 0; i < cidades.length; i++)
            autocomplete_cidades[cidades[i].cidade] = null;
            

        $("input[name='cidade']").autocomplete({
            data: autocomplete_cidades,
            limit: 4
        });
    });

    $("input[name='cidade']").change(checkCidade)

    function checkCidade(){
        var cidade_input = $("input[name='cidade']")


        function existe_cidade() {
            for( var i = 0; i < cidades.length; i++)
            {
                if( cidade_input.val() == cidades[i].cidade )
                    return true
            }

            return false
        }

        if(existe_cidade())
            cidade_input[0].setCustomValidity("")
        else
            cidade_input[0].setCustomValidity("Cidade inválida")
    }

</script>
