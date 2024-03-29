<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>{$title|strip_tags} - {$sys_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/myiphone_ico.png"/>  
		<link rel="StyleSheet" href="{$app_url}cssManager/bootstrap.min.css?v={$ch_ver}" type="text/css"  media="screen">
		<link rel="StyleSheet" href="{$app_url}cssManager/default.css?v={$ch_ver}" type="text/css"  media="screen">
		<link rel="StyleSheet" href="{$app_url}cssManager/fontawesome.min.css?v={$ch_ver}" type="text/css"  media="screen">
		<link rel="StyleSheet" href="{$app_url}cssManager/sweetalert2.min.css?v={$ch_ver}" type="text/css"  media="screen">
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/public/jquery.min.js?v={$ch_ver}"></script>
		<script type="text/javascript">var _APP = {$JS_VARS};</script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/public/utils.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/public/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/public/sweetalert2.all.min.js?v={$ch_ver}"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-10 col-sm-8 col-md-6 col-xl-4 login-div">
					<div id="header_logo"><img src="{$app_url}images/logo.png" /></div>
					<h4 class="mt-3 mb-1"><strong>{$title}</strong></h4>
					<p class="text-left mb-4">{translate l="LBL_INFO_CREATE_NEW_ACCOUNT"}</p>
					<div id="ERROR_LOGIN" class="required">{$login_msg}</div>
					<form name="newForm" id="newForm" method="post" action="{$app_url}login/createUser" autocomplete="off">
						<input type="hidden" name="forgetpass" value="0" id="forgetpass" />
						<p><input name="name" type="text" id="name" class="form-control" placeholder="{translate l="LBL_NAME"} *" autocomplete="off" required maxlength="50"/></p>
						<p><input name="email" type="text" id="email" class="form-control" placeholder="{translate l="LBL_EMAIL"} *" custom_type_validation="email" autocomplete="off" required maxlength="50"/></p>
						<p><input name="senha_nova" type="password" id="senha_nova" class="form-control" placeholder="{translate l="LBL_PASSWORD"} *" autocomplete="off" required maxlength="50"/></p>
						<p><input name="confirm_senha_nova" type="password" id="confirm_senha_nova" class="form-control" placeholder="{translate l="LBL_REPEAT_PASSWORD"} *" autocomplete="off" required maxlength="50"/></p>
						<p class="text-left">** {translate l="LBL_INFO_STRONG_PASSWORD"}</p>
						<p><input class="form-control" type="text" name="telefone" placeholder="{translate l="LBL_TELEPHONE"}" custom_type_validation="telephone" maxlength="14"><script type="text/javascript">$('input[name="telefone"]').mask('(99) 9999-9999');</script></p>
						<p><input class="form-control" type="text" name="celular" placeholder="{translate l="LBL_CELPHONE"}" custom_type_validation="telephone" maxlength="15"><script type="text/javascript">$('input[name="celular"]').mask('(99) 99999-9999');</script></p>
						<p><button type="button" class="btn width-100 btn-outline-success btn-rounded" onclick="ValidateForm('newForm')"><img class="loading-icon" src="{$app_url}images/loading.gif" style="display: none;cursor: not-allowed;width: 1rem;margin-right: 11px;"/> {translate l="LBL_CREATE"}</button></p>
						<p><button type="button" class="btn width-100 btn-outline-info btn-rounded" onclick="location.href='{$app_url}login'"> {translate l="LBL_BACK"}</button></p>
					</form>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="{$app_url}jsManager/Usuarios/criarConta.js?v={$ch_ver}"></script>
</html>