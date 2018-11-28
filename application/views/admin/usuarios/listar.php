<main>
    <h3 class="title">Usuários cadastrados</h2>

    <div class="row">
        <table class="bordered responsive-table">
            <thead>
                <th>ID</th>
                <th>Nome</th>
                <th>Login</th>
                <th>Acesso</th>
                <th>Data de cadastro</th>
                <th>Ações usuário</th>
            </thead>
            <tbody>
                <?php foreach($usuarios as $i => $usuario){?>
                    <tr>
                        <td class="text-center"><?= $usuario->id_usuario ?></td>
                        <td><?= $usuario->nome_usuario ?></td>
                        <td><?= $usuario->login_usuario ?></td>
                        <td class="text-center"><?= $usuario->acesso_usuario ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($usuario->data_cadastro)) ?></td>
                        <td class="text-center">
                            <?php if($usuario->bloqueado){?>
                                <a href="#modal-desbloquear-usuario-<?=$i?>" class="modal-trigger"><i class="material-icons blue-text">undo</i></a>
                            <?php } else{?>
                                <a href="#modal-bloquear-usuario-<?=$i?>" class="modal-trigger"><i class="material-icons red-text">block</i></a>
                            <?php }?>
                        </td>
                    </tr>

                    <?php if($usuario->bloqueado){?>
                        <div id="modal-desbloquear-usuario-<?=$i?>" class="modal">
                            <div class="modal-content center-align">
                                <h5>Você realmente deseja desbloquear o usuário <b><?= $usuario->nome_usuario ?></b></h5>
                            </div>

                            <div class="modal-footer center-align text-center" style="text-align: center !important;">
                                <a class="btn blue white-text" href="<?= base_url("admin/desbloquear/usuario/$usuario->id_usuario")?>">Desbloquear</a>
                                <a class="btn btn-grey grey-text modal-close">Cancelar</a>
                            </div>
                        </div>
                    <?php } else{?>
                        <div id="modal-bloquear-usuario-<?=$i?>" class="modal">
                            <div class="modal-content center-align">
                                <h5>Você realmente deseja bloquear o usuário <b><?= $usuario->nome_usuario ?></b></h5>
                            </div>

                            <div class="modal-footer center-align text-center" style="text-align: center !important;">
                                <a class="btn blue white-text" href="<?= base_url("admin/bloquear/usuario/$usuario->id_usuario")?>">Bloquear</a>
                                <a class="btn btn-grey grey-text modal-close">Cancelar</a>
                            </div>
                        </div>
                    <?php }?>

                    
                <?php }?>
            </tbody>
        </table>
    </div>
</main>