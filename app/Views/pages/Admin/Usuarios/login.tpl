<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Login - {$sys_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/favicon.ico"/>
		<link rel="stylesheet" href="{$app_url}cssManager/bootstrap.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}cssManager/fontawesome-all.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}cssManager/sweetalert2.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}cssManager/default.css?v={$ch_ver}">
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/public/jquery.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/public/jquery-ui-1.12.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/public/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/public/sweetalert2.all.min.js?v={$ch_ver}"></script>
		<script type="text/javascript">var _APP = {$JS_VARS};</script>
		<script type="text/javascript" src="{$app_url}jsManager/public/utils.js?v={$ch_ver}"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-10 col-sm-8 col-md-6 col-xl-4 login-div">
					<div id="header_logo"><img src="{$app_url}images/logo.png" /></div>
					{if $msg_type}
						<div id="ERROR_LOGIN" class="msg-type-{$msg_type}">{$msg}</div>
					{/if}
					<p>Painel de Administração</p>
					<form class="mt-3" name="LoginForm" id="LoginForm" method="post" action="{$app_url}admin/login/auth">
						<input type="hidden" name="rdct_url" value="{$rdct_url}" />
						<input type="hidden" name="forgetpass" value="0" id="forgetpass" />
						<p><input name="email" type="email" id="login" class="form-control" value="" placeholder="Email"/></p>
						<p><input name="senha" type="password" id="senha" class="form-control" value="" placeholder="Senha"/></p>
						<p><button class="btn mt-3 width-100 btn-outline-info btn-rounded" type="submit">Conectar</button></p>
					</form>
					<p class="versao-sistema">ⓒ 2022 Todos os direitos reservados.<br />Desenvolvido por Luis Satsuma | V. {$ch_ver_org}</p>
				</div>
			</div>
		</div>
	</body>
</html>