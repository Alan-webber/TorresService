<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
	@import 'https://fonts.googleapis.com/icon?family=Material+Icons|Oleo+Script';

	.logo{
		color: #3498DB;
		font-family: 'Oleo Script';
		font-weight: normal;
	}

	.btn{
		background: #3498DB;
		box-shadow: none;
		outline: none;
		height: 40px;
		line-height: 40px;
		padding: 0 15px;
		border-radius: 35px;
		color: #fff;
		text-decoration: none;
		transition: all ease-in-out .4s;
		position: relative;
		display: inline-block;
		text-transform: uppercase;
		font-size: 1.1rem;
	}

	body{
		background: #eaeaea;
	}

	main{
		border: 1px solid #ddd;
		padding: 2rem 1rem;
		border-radius:6px;
		max-width: 70%;
		margin: 3rem auto;
		background: #fff;
		text-align: center;
		font-size: 1.4rem;
	}

	.blue{
		color: #3498DB;
	}
</style>



<body>
		
	<main>

		<header>
			<h1 class="logo">TorresService</h1>
		</header>

		<div class="container">
			<p>Olá, <span class="blue"><?= $usuario->nome_usuario ?></span>.</p>

			<p>Para entrar é preciso, primeiro confirmar sua conta.</p>

			<p>Um e-mail de confirmação foi enviado para <span class="blue"><?= $usuario->email_usuario; ?></span></p>

			<br>

			<p><a href="<?= base_url('login') ?>" class="btn">Ir a tela de login</a></p>
		</div>

	</main>

</body>
