<main>

    <h3 class="title">Editar perfil</h2>
    
    <div class="row">
        <a href="#foto-perfil" class="btn blue white-text modal-trigger">Foto</a>    

        <div class="modal" id="foto-perfil">
            
            <form action="<?= base_url('perfil/enviar_foto')?>" method="POST" class="col s12" enctype="multipart/form-data">
                <div class="modal-content">
                    <h4>Editar foto de perfil</h4>

                    <?php                 
                        $perfil = $this->base->getPerfil();

                        if(!empty($perfil)){
                    ?>
                    <div class="row">
                        <br>
                        <div class="col s12">
                            <label for="foto-perfil-input-file">Foto de perfil: </label>
                            <input type="file" name="foto-perfil" id="foto-perfil-input-file">
                        </div>
                    </div>
                    <?php }else{?>
                        <br>
                        <h4 class="red-text">Preencha seus dados em perfil primeiramente!</h4>
                    <?php } ?>
                </div>

                <?php $this->session->set_flashdata('last-uri', $this->uri->uri_string()); ?>

                <div class="modal-footer">
                    <a class="modal-close btn grey white-text">Sair</a>
                    <?php if(!empty($perfil)){?>
                    <input type="submit" value="Atualizar foto" class="btn blue white-text">
                    <?php } ?>

                </div>
            </form>
        </div>

    </div>

    <div class="row">
        <form action="<?= base_url("perfil/editar_usuario")?>" method="POST" class="col s12" id="editar_perfil">
            <div class="row">
                <div class="input-field col s12 m6">
                    <input name="nome" id="first_name" type="text" class="validate" value="<?= $usuario->nome_usuario?>" required>
                    <label for="first_name">Nome completo</label>
                </div>
                <div class="input-field col s12 m6">
                    <input name="email" id="email" type="email" class="validate" value="<?= $usuario->email_usuario?>" required>
                    <label for="email">Email</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6">
                    <input name="senha" id="password" type="password" class="validate">
                    <label for="password">Senha</label>
                </div>
                <div class="input-field col s12 m6">
                    <input name="confirmar_senha" id="conf_password" type="password" class="validate">
                    <label for="conf_password">Confirmar Senha</label>
                </div>
            </div>
            

            <div class="row">
                <div class="input-field col s12">
                    <p>
                        <label>
                            <input type="radio" name="pessoaFisicaJurica" id="pessoaFisica" class="pessoaFisicaJurica" value="pessoaFisica" <?= (($usuario_perfil->pessoa_fisica_juridica) ? "checked" : "")?>>
                            <span>Pessoa Física</span>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input type="radio" name="pessoaFisicaJurica" id="pessoaJuridica" class="pessoaFisicaJurica" value="pessoaJuridica" <?= (($usuario_perfil->pessoa_fisica_juridica == FALSE) ? "checked" : "")?>>
                            <span>Pessoa Jurídica</span>
                        </label>
                    </p>
                </div>
            </div>

            <div class="row pessoaJuridica <?= (($usuario_perfil->pessoa_fisica_juridica) ? "hide" : "")?> pessoaFisicaJuridicaRow">
                <div class="input-field col s12 m6">
                    <input name="cnpj" id="cnpj" type="text" class="validate" value="<?= $usuario_perfil->cnpj ?>">
                    <label for="cnpj">CNPJ</label>
                </div>
                <div class="input-field col s12 m6">
                    <input name="inscricaoEstadual" id="inscricaoEstadual" type="text" class="validate" value="<?= $usuario_perfil->inscricao_estadual ?>">
                    <label for="inscricaoEstadual">Inscricao Estadual</label>
                </div>
            </div>

            <div class="row pessoaFisica <?= (($usuario_perfil->pessoa_fisica_juridica == FALSE) ? "hide" : "")?> pessoaFisicaJuridicaRow">
                <div class="input-field col s12 m6">
                    <input name="cpf" id="cpf" type="text" class="validate" value="<?= $usuario_perfil->cpf ?>" required>
                    <label for="cpf">CPF</label>
                </div>
                <div class="input-field col s12 m6">
                    <input name="rg" id="rg" type="text" class="validate" value="<?= $usuario_perfil->rg ?>" required>
                    <label for="rg">RG</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m6">
                    <input name="telefone1" id="telefone1" type="text" class="validate" value="<?= $usuario_perfil->telefone1 ?>" required>
                    <label for="telefone1">Telefone 1</label>
                </div>
                <div class="input-field col s12 m6">
                    <input name="telefone2" id="telefone2" type="text" class="validate" value="<?= $usuario_perfil->telefone2 ?>">
                    <label for="telefone2">Telefone 2</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m3">
                    <input name="endereco" id="endereco" type="text" class="validate" value="<?= $usuario_perfil->endereco ?>" required>
                    <label for="endereco">Endereço</label>
                </div>
                <div class="input-field col s12 m2">
                    <input name="complemento" id="complemento" type="text" class="validate" value="<?= $usuario_perfil->complemento ?>">
                    <label for="complemento">Complemento</label>
                </div>
                <div class="input-field col s12 m2">
                    <input name="cep" id="cep" type="text" class="validate" value="<?= $usuario_perfil->cep ?>" required>
                    <label for="cep">CEP</label>
                </div>

                <div class="input-field col s12 m4">
                    <input type="text" name="cidade" id="cidade" class="autocomplete" value="<?= $usuario_perfil->cidade ?>" required>
                    <label for="cidade">Cidade</label>
                </div>
                <div class="input-field col s12 m1">
                    <input name="numero" id="numero" type="text" class="validate" value="<?= $usuario_perfil->numero ?>" required>
                    <label for="numero">Número</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12 m6">
                    <input name="profissao" id="profissao" type="text" class="validate" value="<?= $usuario_perfil->profissao ?>">
                    <label for="profissao">Profissão</label>
                </div>
                <div class="input-field col s12 m6">
                    <input name="funcao" id="funcao" type="text" class="validate" value="<?= $usuario_perfil->funcao ?>">
                    <label for="funcao">Função</label>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <input type="submit" id="" value="Editar" class="btn blue white-text">
                </div>
            </div>
        </form>
    </div>
</main>


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

    $('#conf_password').on('keyup', function(e){
        if($(e.target).val().length > 0 && $(e.target).val() != $("#password").val()){
            $(e.target)[0].setCustomValidity("As senhas não conferem");
        }
        else
        {
            $(e.target)[0].setCustomValidity("");
        }
    })

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
