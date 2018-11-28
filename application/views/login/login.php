<main id="login" class="blue">
    <div class="row content">
        <form action="<?= base_url('login/entrar') ?>" method="POST" class="form col s12 m6 z-depth-2 white">
            <div class="col s12">
                <h4 class="center brand logo blue-text" style="text-transform: initial">TorresService</h4>
            </div>
            <h4 class="font-logo blue-text">Login</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" name="usuario" id="usuario" required>
                    <label for="usuario">Usuário</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="password" name="senha" id="senha" required>
                    <label for="senha">Senha</label>
                </div>
            </div>
            <div class="row valign-wrapper">
                <div class="input-field col m6 s12">
                    <a href="<?= base_url('login/registrar')?>">Cadastre-se aqui</a>
                </div>
                <div class="input-field col s12 m4 right">
                    <input type="submit" class="btn right blue white-text" value="Entrar">
                </div>
            </div>
        </form>
    </div>
</main>