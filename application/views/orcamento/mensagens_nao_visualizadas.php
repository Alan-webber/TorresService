<main>
    <h3 class="title">Mensagens não visualizadas</h2>

    <?php  foreach ($mensagens as $key => $mensagem) { ?>
        <div class="row">
            <div class="col s12">

                <a href="<?= base_url("orcamento/visualizar/$mensagem->id_orcamento")?>" class="link-servico">
                    <div class="card-servico" style="height: auto; margin-bottom: 0">
                        <div class="descricao">
                            <p><b><?= $mensagem->quantidade?></b> mensagens não visualizadas.</p>
                            <p>Orçamento(<?= $mensagem->titulo_servico?> / <?= $mensagem->id_orcamento ?>)</p>
                            <p><?= ($this->base->isProfissional() ? 'Cliente: ' : 'Prestador de serviço: ') . $mensagem->nome_usuario?></p>
                        </div>
                    </div>
                </a>
                
            </div>
        </div>
    <?php } ?>

</main>