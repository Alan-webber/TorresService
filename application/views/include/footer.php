<footer>
    <div class="row" style="margin-bottom: 0">
        <div class="col s12">
            <p>@copyleft 2018 Torres Service. Todos os direitos reservados. (51)9 9171-7018 | contato.torresservice@gmail.com</p>
        </div>
    </div>
</footer>

<script src="<?= base_url('assets/js/jquery.mask.min.js') ?>"></script>

<script src="<?= base_url('assets/js/slick.min.js')?>"></script>

<script src="<?= base_url('assets/js/main.js?v=2')?>"></script>


<script>
    $(window).ready(function(){
        var offsetTop = $('footer').offset().top;
        var windowHeigh = ($(window).height() - $('.navbar').height() - ($('footer').height() / 2));

        if(windowHeigh > offsetTop)
        {
            $('footer').css({
                'position': 'fixed',
                'top': windowHeigh + 'px'
            })
        }
    })
</script>

<?php if(!empty($this->session->flashdata('error')) || !empty($this->session->flashdata('success'))){ ?>
<script>
    M.toast({html: "<?= (!empty($this->session->flashdata('success')) ?  $this->session->flashdata('success') : $this->session->flashdata('error') ) ?>", classes: "rounde tooltip-<?= (!empty($this->session->flashdata('success')) ?  'success' : 'error') ?>"});
</script>
<?php }?>

</body>
</html>