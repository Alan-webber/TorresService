<?php
    if(!empty($this->session->flashdata('cadastro')))
        $cadastro = $this->session->flashdata('cadastro');
?>

<main id="login" class="blue">
    <div class="row content">
        <form action="<?= base_url('login/cadastrar')?>" method="POST" class="form col s11 m8 z-depth-2 white">
            <div class="col s12">
                <h4 class="center brand logo blue-text" style="text-transform: initial">TorresService</h4>
            </div>
            <h4 class="font-logo blue-text">Crie sua conta</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" name="nome" id="nome" required value="<?= (!empty($cadastro['nome'])? $cadastro['nome'] : "")?>">
                    <label for="nome">Nome completo</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" name="email" id="email" required value="<?= (!empty($cadastro['email'])? $cadastro['email'] : "")?>">
                    <label for="email">Insira seu e-mail</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" name="usuario" id="usuario" required value="<?= (!empty($cadastro['usuario'])? $cadastro['usuario'] : "")?>">
                    <label for="usuario">Usuário</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <select name="acesso" id="acesso" required>
                        <option value="" disabled <?= (empty($cadastro['acesso'])? "selected" : "") ?>>Escolha o seu perfil</option>
                        <option value="profissional" <?= (!empty($cadastro['acesso']) && $cadastro['acesso'] == 'profissional' ? "selected" : "") ?>>Prestador de serviço</option>
                        <option value="cliente" <?= (!empty($cadastro['acesso']) && $cadastro['acesso'] == 'cliente' ? "selected" : "") ?>>Cliente</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="password" name="senha" id="senha" required>
                    <label for="senha">Senha</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="password" name="confirmar_senha" id="confirma_senha" required>
                    <label for="confirma_senha">Confirme sua senha</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <p>
                        <label>
                            <input type="checkbox" name="concordo_termo" class="filled-in blue" required/>
                            <span>Li e concordo com os <a href="<?= base_url('termo') ?>" target="_blank">termos.</a></span>
                        </label>
                    </p>
                </div>
            </div>
            <div class="row valign-wrapper">
                <div class="input-field col m6 s12">
                    <a href="<?= base_url('login')?>">Já possui cadastro</a>
                </div>
                <div class="input-field col s12 m4 right">
                    <input type="submit" class="btn right blue white-text" value="Registrar">
                </div>
            </div>
        </form>
    </div>
</main>