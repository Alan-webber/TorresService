// Inicialização
$(document).ready(function(){
    
    // Select
    $('select').formSelect()

    // Menu sidenav
    $('.sidenav').sidenav()

    // Modal
    $('.modal').modal()

    // Carousel
    $('.single-item').slick()
    
    // Date
    $('.timepicker').timepicker({
        twelveHour: false
    });

    // Tooltip
    $('.tooltipped').tooltip();

    // DropDown
    $('.dropdown-trigger').dropdown();

    togglePessoaFisicaJuridica($('input.pessoaFisicaJurica'))

    // Masks
    inputMask();

    attrModals();

    validacaoTelefone();
})

function validacaoTelefone() {
    $("input[name ^= 'telefone']").on('keyup', function () {
        var phone = $(this).val().replace(/[^0-9]/g, '');
        if (phone.length < 10)
            $(this)[0].setCustomValidity("Número de telefone inválido");
        else
            $(this)[0].setCustomValidity("");
        if (phone.length > 10)
            $(this).mask('(00) 90000-0000');
        else
            $(this).mask('(00) 0000-00009');
    });
}

function attrModals(){
    var modals = $('.modal');

    for(var i = 0; i < modals.length; i++){
        if($(modals[i]).attr('open') != undefined){
            $(modals[i]).modal('open');
        }
    }
}

$('nav .menu-categorias a.open').click(e => {   
    e.preventDefault();

    var menu = $('nav .menu-categorias .categorias');

    var cap = $('nav .menu-categorias .cap-categorias');

    if(menu.hasClass('active'))
    {
        menu.removeClass('active')
        cap.removeClass('active')
        $(e.currentTarget).removeClass('active')
    }
    else
    {
        menu.addClass('active')
        cap.addClass('active')
        $(e.currentTarget).addClass('active')
    }
})


$('nav .menu-categorias a.open').focusout(e => {   
    e.preventDefault();

    var menu = $('nav .menu-categorias .categorias');

    var cap = $('nav .menu-categorias .cap-categorias');

    if(menu.hasClass('active'))
    {
        menu.removeClass('active')
        cap.removeClass('active')
        $(e.currentTarget).removeClass('active')
    }
})

$('nav .nav-wrapper .search').click(e => {
    if(isMobile())
    {        
        e.preventDefault();
    
        var search = $('nav .nav-wrapper .search form #search');
    
        if(!$(e.currentTarget).hasClass('focusIn'))
        {
            search.addClass('focusIn')
            $('nav .nav-wrapper .search form label').addClass('focusIn')
            search.focus();
        }
    }
})

$('nav .nav-wrapper .search').focusout(e => {
    if(isMobile())
    {
        e.preventDefault();
    
        var search = $('nav .nav-wrapper .search form #search');
    
        search.removeClass('focusIn')
        $('nav .nav-wrapper .search form label').removeClass('focusIn')
    }
})


$('.btn-back').click(e => {
    e.preventDefault()
    
    history.back()
})

$('.icones-categorias .icone').click(e => {
    e.preventDefault();

    name = $(e.currentTarget).data('icone');

    $('.icones-categorias .icone').removeClass('active');

    $(e.currentTarget).addClass('active');

    $('input[name="icon_name"').val(name);

    $('input[type="submit"]').removeAttr('disabled');
})

function inputMask()
{
    var inputs = [
        {name: "cpf", mask: '000.000.000-00'},
        {name: "cnpj", mask: '00.000.000/0000-00'},
        {name: "cep", mask: '00000-000'},
        // {name: ["telefone", "telefone1", "telefone2"], mask: '(99) 9999-99999'},
        {name: "numero", mask: '99999'}
    ];

    inputs.map(function (value, index, array){
        mask = value.mask;

        if( Array.isArray(value.name) == true )
        {
            value.name.map(function(element, i, object){
                $("input[name='"+element+"'").mask(mask);
            })
        }
        else
        {
            $("input[name='"+value.name+"'").mask(mask);
        }
    })
}

function togglePessoaFisicaJuridica(_this)
{
    if(_this != undefined)
    {
        _this.click(e => {
            pessoaFisicaJuridicaPerfil(e.currentTarget)
        })
    }
}

function pessoaFisicaJuridicaPerfil(_this)
{
    var row = $('.pessoaFisicaJuridicaRow')

    row.removeClass('show')
    row.addClass('hide')

    $('.pessoaFisicaJuridicaRow input').prop('required', false)
    
    $(`.${_this.getAttribute('id')}`).removeClass('hide')

    $(`.${_this.getAttribute('id')} input`).prop('required', true)
}

var nButton = 1;

function removeButton(last){
    if($('.file-field').length == 1)
    {
        return false;
    }
    
    $(`.input-file-${last}`).remove();
}

function addButton(local, last){
    if($(`#file-path-${last}`).val() == ''){
        $(`.input-file-${last}`).remove();
    } else{
        $(`#onlyButton${last}`).addClass('check');
        $(`#onlyButton${last} .add i.material-icons`).html('check');
        $(`#onlyButton${last} .number`).css({'color': '#fff'});

        button = 
        `<div class="file-field file-field input-file-${nButton}">
            <div class="onlyButton" id="onlyButton${nButton}">
                <span class="delete" onclick="removeButton(${nButton})"><i class="material-icons">close</i></span>
                <span class="number">${nButton+1}</span>
                <span class="add"><i class="material-icons" style="font-size: 1.5em">add</i></span>
                <input type="file" class="file" name="fotos[]" id="file-${nButton}">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" id="file-path-${nButton}" onchange="addButton('.fileSpace', ${nButton})">
            </div>
        </div>`;

        $(local).append(button);

        nButton++;  
    }

}

var anexosExcluir = new Array();

$('.excluir-anexo-edit').click(function(){
    var key = this.getAttribute('data-key');

    var id = this.getAttribute('data-id');

    var nameFile = $(`#line-name-anexo-edit-${key}`);

    nameFile.addClass('deleted');

    showLinkAnexo(true, key, false)
    showLinkAnexo(false, key, true);

    inputExcluir(true, id);
});

$('.return-anexo-edit').click(function(){
    var key = this.getAttribute('data-key');

    var id = this.getAttribute('data-id');

    var nameFile = $(`#line-name-anexo-edit-${key}`);

    nameFile.removeClass('deleted');

    showLinkAnexo(true, key, true)
    showLinkAnexo(false, key, false);

    inputExcluir(false, id);
});

function inputExcluir(excluirOrRetornar, id){
    var input = $('#anexosExcluirId');
    
    if(excluirOrRetornar)
        anexosExcluir.push(id);
    else
        removeAnexoArray(id);

    input.val(anexosExcluir.join(';'));
}

function removeAnexoArray(pId){
    var input = $('#anexosExcluirId');

    var newArray = new Array();

    var finded = false;

    console.log(anexosExcluir);

    for(var i = 0; i < anexosExcluir.length; i++){
        if(anexosExcluir[i] != pId && !finded){
            newArray.push(anexosExcluir[i]);
            finded = true;
        }
    }

    console.log(newArray);

    anexosExcluir = newArray;

    input.val(anexosExcluir.join(';'));
}

function showLinkAnexo(deleteOrReturn, key, showOrHide){
    if(deleteOrReturn)
        var link = $('.excluir-anexo-edit');
    else
        var link = $('.return-anexo-edit');
        
    for(var i = 0; i < link.length; i++)
    {
        if(link[i].getAttribute('data-key') == key)
        {
            if(showOrHide)
                $(link[i]).removeClass('hide');
            else
                $(link[i]).addClass('hide');
        }
    }
}

function isMobile()
{
    return $(this).height() <= 992
}