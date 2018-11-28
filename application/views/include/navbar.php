<nav class="navbar">
    <div class="nav-wrapper">
        <a href="<?= base_url() ?>" class="brand-logo">Torres Service</a>

        <?php if($this->base->is_logado()){?>
        <a href="#" data-target="slide-out" class="sidenav-trigger tooltipped" data-position="bottom" data-tooltip="Menu">
            <i class="material-icons">menu</i>
        </a>
        <?php } else{?>
        <a class="btn-entrar blue-text only-mobile" href="<?=base_url('login')?>">Entrar</a>
        <?php }?>
        

        <div class="search">
            <form action="<?= base_url("servicos/buscar") ?>" method="GET">
                <label for="search"><i class="material-icons">search</i></label>
                <input type="search" name="search" id="search" class="input-search" placeholder="Pesquisar..." value="<?= (!empty($this->input->get('search')) ? $this->input->get('search') : "") ?>">
            </form>
        </div>

        <div class="nav-right right">
            <?php if($this->base->isProfissional()){?>
            <a href="<?= base_url('servicos/cadastrar')?>" class="nav-action">
                <i class="material-icons">add_circle_outline</i>
                <span>Novo serviço</span>
            </a>
            <?php }?>

            <div class="menu-categorias">
                <div class="click">
                    <a href="#categorias" class="nav-action open">
                        <i class="material-icons">style</i>
                        <span>Categorias</span>
                    </a>
                    
                </div>

                <div class="cap-categorias"></div>

                <div class="categorias">
                    <?php foreach ($this->base->getCategoriasMenu() as $key => $categoria) { ?>
                        <?php 
                            if($key == 0)
                            {
                                $count = 0;
                                $row = true;
                            }
                            else if($key > 0 && $key % 5 == 0)
                            {
                                $count = $key + 1;
                                $row = true;
                            }
                            else
                            {
                                $row = false;
                            }
                        ?>
                        <?= ($row && $key > 0) ? "</div>" : ""?>

                        <?= ($row) ? "<div class=\"row\">" : ""?>
                            <a title="<?= $categoria->nome_categoria_servico?>" href="<?= base_url("servicos/exibir/$categoria->id_categoria_servico") ?>" class="item text-ellipsis">
                                <i class="material-icons"><?= $categoria->icon_name?></i>
                                <span><?= $categoria->nome_categoria_servico?></span>
                            </a>                        
                    <?php }?>
                </div>

            </div>
        </div>
        
        <div class="perfil">
            <?php if($this->base->is_logado()){?>
                <a class="loged" href="<?= base_url("perfil/editar") ?>">
                    <?php if(!empty($this->base->getFotoPerfil())){?>
                        <span class="foto circle"><img src="<?= base_url($this->base->getFotoPerfil())?>"></span>
                    <?php }?>
                    <span class="person"><?= explode(" ", $this->session->userdata('nome'))[0]?></span>
                </a>
            <?php }else{?>
                <a class="blue-text" href="<?=base_url('login')?>">Entrar</a>
            <?php }?>
        </div>
    </div>
</nav>

<?php if($this->base->is_logado()){?>
<ul id="slide-out" class="sidenav">
    <div class="menu">
        <li>
            <div class="user-view">
                <span class="name"><?= $this->session->userdata('nome')?></span>
                <span class="email"><?= $this->session->userdata('email')?></span>
            </div>
        </li>
        <li>
            <a href="<?= base_url() ?>">
                <i class="material-icons left">home</i> Home
            </a>
        </li>
        <li>
            <a href="<?= base_url('perfil/editar') ?>">
                <i class="material-icons left">person</i> Perfil
            </a>
        </li>
        <?php if($this->base->isProfissional()){?>
        <li>
            <a href="<?= base_url('profissional/orcamento/listar') ?>">
                <i class="material-icons left">vertical_split</i> Orçamentos
                <?php 
                    $count_novos_orcamentos = $this->base->orcamentos_nao_visualizados();
                if($count_novos_orcamentos > 0){?>
                <span class="new badge blue" data-badge-caption="novo<?= $count_novos_orcamentos > 1 ? 's' : '' ?> "><?= $count_novos_orcamentos ?></span>
                <?php }?>
            </a>
        </li>
        <?php }?>
        <?php if($this->base->isCliente()){?>
        <li>
            <a href="<?= base_url('cliente/orcamento/listar') ?>">
                <i class="material-icons left">vertical_split</i> Orçamentos
            </a>
        </li>
        <?php }?>
        <?php if($this->base->isProfissional() || $this->base->isCliente()){ ?>
        <li>
            <a class="text-ellipsis" href="<?= base_url('orcamento/mensagens_nao_visualizadas') ?>" title="Mensagens não visualizadas">
                <?php 
                    $count_novas_mensagens = $this->base->mensagens_nao_visualizadas();
                if($count_novas_mensagens > 0){?>
                <span class="new badge blue" data-badge-caption="nova<?= $count_novas_mensagens > 1 ? 's' : '' ?> "><?= $count_novas_mensagens ?></span>
                <?php }?>
                <i class="material-icons left">chat</i> Mensagens não visualizadas
            </a>
        </li>
        <?php }?>
        <?php if(in_array($this->session->userdata('acesso'), ['profissional'])){?>
        <li>
            <a href="<?= base_url('servicos/listar') ?>">
                <i class="material-icons left">settings</i> Serviços
            </a>
        </li>
        <?php }?>
        <?php if(in_array($this->session->userdata('acesso'), ['cliente', 'profissional', 'admin'])){?>
        <li>
            <a href="<?= base_url('servicos/listar_favoritos') ?>">
                <i class="material-icons left">star</i> Meus favoritos
            </a>
        </li>
        <?php }?>
        <?php if($this->base->isAdmin()){?>
        <li>
            <a href="<?= base_url('admin/listar_categorias') ?>">
                <i class="material-icons left">style</i> Categorias
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/listar_usuarios') ?>">
                <i class="material-icons left">people</i> Usuários
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/denuncias')?>">
                <i class="material-icons left">block</i> Denuncias
            </a>
        </li>
        <?php }?>
        <?php if(!$this->base->isAdmin()){?>
        <li>
            <a href="<?= base_url('categorias')?>">
                <i class="material-icons left">style</i> Categorias
            </a>
        </li>
        <?php }?>
        <li>
            <a href="<?= base_url('login/sair')?>">
                <i class="material-icons left">power_settings_new</i> Sair
            </a>
        </li>
       
    
        <li id="termo">
            <a href="<?= base_url('termo') ?>">Termos e condições gerais</a>
        </li>
    </div>
</ul>
<?php }?>

<!-- <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a> -->