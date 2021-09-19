<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Satsuma Karaoke</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/favicon.ico"/>
		<link rel="stylesheet" href="{$app_url}cssManager/bootstrap.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}cssManager/fontawesome-all.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}cssManager/sweetalert2.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}cssManager/default.css?v={$ch_ver}">
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-3.5.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-ui-1.12.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/sweetalert2.all.min.js?v={$ch_ver}"></script>
		<script type="text/javascript">var _app_vars = {$JS_VARS};</script>
		<script type="text/javascript" src="{$app_url}jsManager/utils.js?v={$ch_ver}"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-10 col-sm-8 col-md-6 col-xl-4 login-div">
					<div id="header_logo"><img src="{$app_url}images/logo.png" /></div>
					<div id="ERROR_LOGIN" class="required">{$login_msg}</div>
					<form id="resetSenha" method="post" action="{$app_url}login/save_fgt_pass" class="user" novalidate="novalidate" pb-autologin="true" autocomplete="off">
					<div class="col-12 mx-auto">
						<div class="box-login">
							<input type="hidden" name="id" value="{$id}" />
							<input type="hidden" name="hash" value="{$hash}" />
							<div class="col-12 text-center">
								<h3>Alteração de Senha</h3>
							</div>
							<div class="row">
								<div class="col-12 mb-1 mt-1 mx-auto" style="padding: 0px;max-width: 400px">
									<p><input type="text" class="form-control" value="{$email}" disabled="true"/></p>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb-1 mt-1 mx-auto" style="padding: 0px;max-width: 400px">
									<p><input name="nova_senha" type="password" id="nova_senha" class="form-control" value="" placeholder="Nova Senha"/></p>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb-1 mt-1 mx-auto" style="padding: 0px;max-width: 400px">
									<p><input name="confirm_nova_senha" type="password" id="confirm_nova_senha" class="form-control" value="" placeholder="Confirme a Nova Senha"/></p>
								</div>
							</div>
							<p class="text-center">
								<button class="btn btn-outline-success btn-rounded" type="button" pb-role="submit" onclick="ValidateForm('resetSenha')">ALTERAR</button>
							</p>
							<p id="links" class="text-center">
								<a href="{$app_url}login">Voltar</a><br>
							</p>
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	<script type="text/javascript" src="{$app_url}js/Usuarios/resetSenha.js?v={$ch_ver}"></script>
	</body>
</html>