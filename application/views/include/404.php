<style>

.page404.container-fluid{
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgb(221, 242, 253);
}

.page404 .page{
    display: flex;
    align-items: center;
    justify-content: center;
}

.page404 .content{
    display: block;
    text-align: center;
    margin-top: 8em;
}

.page404 .row *{
    text-align: center;
    margin: 0 auto;
}

.page404 .number{
    font-size: 8em;
    font-weight: bold;
    color: #fff;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}

.page404 .title{
    font-size: 2em;
    font-weight: lighter;
    color: #fff;
}

.page404 .paragraphy{
    font-size: 1.2em;
    color: #fff;
    margin: 2em 0 1em 0;
}
</style>

<div class="container-fluid page404">
    <div class="page">
        <div class="content">
            <div class="row">
                <div class="number blue-text">404</div>
            </div>
            <div class="row">
                <div class="title blue-text">Página não encontrada</div>
            </div>
            <div class="row">
                <p class="paragraphy blue-text">Por favor, tente voltar a página ou ir a página inicial</p>
            </div>
            <div class="row">    
                <p><a href="<?= base_url() ?>" class="btn blue white-text">Página inicial</a></p>
            </div>
        </div>
    </div>
</div>