<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Satsuma Karaokê</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/myiphone_ico.png"/>  
		<link rel="StyleSheet" href="{$app_url}css/bootstrap.min.css?v={$ch_ver}" type="text/css"  media="screen">
		<link rel="StyleSheet" href="{$app_url}css/default.css?v={$ch_ver}" type="text/css"  media="screen">
		<link rel="StyleSheet" href="{$app_url}css/fontawesome.min.css?v={$ch_ver}" type="text/css"  media="screen">
		<link rel="StyleSheet" href="{$app_url}css/sweetalert2.min.css?v={$ch_ver}" type="text/css"  media="screen">
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-3.5.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/utils.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/sweetalert2.all.min.js?v={$ch_ver}"></script>
		<script type="text/javascript">var app_url = '{$app_url}';</script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-10 col-sm-8 col-md-6 col-xl-4 login-div">
					<div id="header_logo"><img src="{$app_url}images/logo.png" /></div>
					<h4><strong>Criar uma nova Conta</strong></h4>
					<div id="ERROR_LOGIN" class="required">{$login_msg}</div>
					<form name="newForm" id="newForm" method="post" action="{$app_url}login/createUser" autocomplete="off">
						<input type="hidden" name="forgetpass" value="0" id="forgetpass" />
						<p><input name="nome" type="text" id="nome" class="form-control" value="" placeholder="Nome" autocomplete="off"/></p>
						<p><input name="email" type="text" id="email" class="form-control" value="" placeholder="Email" autocomplete="off"/></p>
						<p><input name="senha" type="password" id="senha" class="form-control" value="" placeholder="Senha" autocomplete="off"/></p>
						<p><input name="senha_repeat" type="password" id="senha_repeat" class="form-control" value="" placeholder="Repita a Senha" autocomplete="off"/></p>
						<p><button type="button" class="btn form-control btn-success" ><img class="loading-icon" src="{$app_url}images/loading.gif" style="display: none;cursor: disabled;width: 1rem;margin-right: 11px;"/> Cadastrar</button></p>
						<p><button type="button" class="btn form-control btn-info" onclick="location.href='{$app_url}login'"> Voltar</button></p>
					</form>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="{$app_url}jsManager/Usuarios/criarConta.js?v={$ch_ver}"></script>
</html>