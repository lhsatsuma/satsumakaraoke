<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Satsuma Karaoke</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/favicon.ico"/>
		<link rel="stylesheet" href="{$app_url}css/bootstrap.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/default.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/fontawesome-all.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/sweetalert2.min.css?v={$ch_ver}">
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-3.5.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-ui-1.12.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/sweetalert2.all.min.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/utils.js?v={$ch_ver}"></script>
		<script type="text/javascript">
			var app_url = '{$app_url}';
		</script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-10 col-sm-8 col-md-6 col-xl-4 login-div">
					<div id="header_logo"><img src="{$app_url}images/logo.png" /></div>
					<div id="ERROR_LOGIN" class="required">{$login_msg}</div>
					<p>Painel de Administração</p>
					<form name="LoginForm" id="LoginForm" method="post" action="{$app_url}admin/login/auth">
						<input type="hidden" name="forgetpass" value="0" id="forgetpass" />
						<p><input name="email" type="text" id="login" class="form-control" value="" placeholder="Email"/></p>
						<p><input name="senha" type="password" id="senha" class="form-control" value="" placeholder="Senha"/></p>
						<p><input class="button form-control" type="submit" value="Enviar"/></p>
						<p><a href="javascript:void(0)" onclick="ForgetPassword()">Esqueci minha senha</a></p>
						<p><a href="javascript:void(0)" onclick="CriarConta()">Criar Conta</a></p>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>