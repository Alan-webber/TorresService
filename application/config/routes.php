<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$Controllers = array(
    'usuario'       => 'Usuario_Controller',
    'perfil'        => 'Perfil_Controller',
    'profissional'  => 'Profissional_Controller',
    'cliente'       => 'Cliente_Controller',
    'servicos'      => 'Servicos_Controller',
    'home'          => 'Home_Controller',
    'admin'         => 'Admin_Controller',
    'orcamento'     => 'Orcamento_Controller'
);

/* Routes Default */
$route['default_controller']    = "{$Controllers['home']}/index";
$route['404_override']          = "{$Controllers['usuario']}/error404";
$route['translate_uri_dashes']  = FALSE;

/* Usuário */
$route['login']             = "{$Controllers['usuario']}/screenLogin";
$route['login/registrar']   = "{$Controllers['usuario']}/screenRegister";
$route['login/cadastrar']   = "{$Controllers['usuario']}/register";
$route['login/entrar']      = "{$Controllers['usuario']}/login";
$route['login/sair']        = "{$Controllers['usuario']}/sair";

$route['testar']        = "{$Controllers['usuario']}/teste";

$route['aviso_confirmacao_conta/(:any)'] = "{$Controllers['usuario']}/aviso_confirmacao_conta/$1";

/* Acessos */
$route['profissional']  = "{$Controllers['profissional']}/index";
$route['cliente']       = "{$Controllers['cliente']}/index";

/* Perfil */
$route['perfil/editar']         = "{$Controllers['perfil']}/editar_perfil";
$route['perfil/editar_usuario'] = "{$Controllers['perfil']}/editar_formulario";
$route['perfil/enviar_foto']    = "{$Controllers['perfil']}/enviar_foto";

/* Serviços */
$route['servicos']                  = "{$Controllers['servicos']}";
$route['servicos/listar']           = "{$Controllers['servicos']}/listar";
$route['servicos/listar_favoritos'] = "{$Controllers['servicos']}/listar_favoritos";
$route['servicos/cadastrar']        = "{$Controllers['servicos']}/cadastrar";
$route['servicos/denunciar/(:num)'] = "{$Controllers['servicos']}/denunciar/$1";
$route['servicos/editar/(:num)']    = "{$Controllers['servicos']}/editar/$1";
$route['servicos/excluir/(:num)']   = "{$Controllers['servicos']}/excluir/$1";
$route['servicos/exibir/(:num)']    = "{$Controllers['servicos']}/exibir/$1";
$route['servicos/detalhes/(:num)']  = "{$Controllers['servicos']}/detalhes_servico/$1";
$route['servicos/favoritar/(:num)'] = "{$Controllers['servicos']}/favoritar_servico/$1";
$route['servicos/imagem/(:num)']    = "{$Controllers['servicos']}/imagem/$1";
$route['servicos/buscar']           = "{$Controllers['usuario']}/buscar";

/* Home */
$route['inicial']    = "{$Controllers['home']}/index";
$route['categorias'] = "{$Controllers['home']}/categorias";

/* Admin */
$route['admin/listar_categorias']           = "{$Controllers['admin']}/listar_categorias";
$route['admin/cadastrar_categoria']         = "{$Controllers['admin']}/cadastrar_categoria";
$route['admin/excluir_categoria/(:num)']    = "{$Controllers['admin']}/excluir_categoria/$1";
$route['admin/inserir_categoria']           = "{$Controllers['admin']}/inserir_categoria";
$route['admin/denuncias']                   = "{$Controllers['admin']}/denuncias";
$route['admin/denuncias/servico/(:num)']    = "{$Controllers['admin']}/denuncias_servico/$1";
$route['admin/inativar/servico/(:num)']     = "{$Controllers['admin']}/inativar_servico/$1";
$route['admin/ativar/servico/(:num)']       = "{$Controllers['admin']}/ativar_servico/$1";
$route['admin/bloquear/usuario/(:num)']     = "{$Controllers['admin']}/bloquear_usuario/$1";
$route['admin/desbloquear/usuario/(:num)']  = "{$Controllers['admin']}/desbloquear_usuario/$1";
$route['admin/listar_usuarios']             = "{$Controllers['admin']}/listar_usuarios";

/* Termo */
$route['termo'] = "{$Controllers['usuario']}/termo";


/* Orçamento */
$route['orcamento/novo_orcamento/(:num)']       = "{$Controllers['orcamento']}/novo_orcamento/$1";
$route['orcamento/visualizar/(:num)']           = "{$Controllers['orcamento']}/visualizar/$1";
$route['orcamento/enviar_mensagem/(:num)']      = "{$Controllers['orcamento']}/enviar_mensagem/$1";
$route['orcamento/finalizar/(:num)']            = "{$Controllers['orcamento']}/finalizar/$1";
$route['profissional/orcamento/listar']         = "{$Controllers['orcamento']}/listar";
$route['cliente/orcamento/listar']              = "{$Controllers['orcamento']}/listar";
$route['servico/avaliar/(:num)']                = "{$Controllers['orcamento']}/avaliar/$1";
$route['orcamento/mensagens_nao_visualizadas']  = "{$Controllers['orcamento']}/listar_mensagens_nao_visualizadas";



$route['email'] = "Usuario_Controller/teste";