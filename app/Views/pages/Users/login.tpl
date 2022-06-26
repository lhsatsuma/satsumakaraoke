<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>{$title|strip_tags} - {$sys_title}</title>
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
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/public/translate.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript">const translate = new translateApp('{$default_lang_file}');</script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/Languages/{$locale}/Public/compressed_lang.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/Languages/{$locale}/Controllers/compressed_lang.js?v={$ch_ver}"></script>
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
					<p class="mt-3 text-left">{translate l="LBL_INFO_LOGIN"}</p>
					<form class="mt-3" name="LoginForm" id="LoginForm" method="post" action="{$app_url}login/auth">
						<input type="hidden" name="rdct_url" value="{$rdct_url}" />
						<input type="hidden" name="forgetpass" value="0" id="forgetpass" />
						<p><input name="email" type="email" id="login" class="form-control" value="" placeholder="Email"/></p>
						<p><input name="senha" type="password" id="senha" class="form-control" value="" placeholder="Senha"/></p>
						<p><button class="btn mt-3 width-100 btn-outline-info btn-rounded" type="submit">{translate l="LBL_CONNECT"}</button></p>
						<p><a href="javascript:void(0)" onclick="OpenModalReset()">{translate l="LBL_FORGOT_PASSWORD"}</a></p>
						{if $enabled_create_user}
							<p><a href="{$app_url}login/createAccount" >{translate l="LBL_CREATE_ACCOUNT"}</a></p>
						{/if}
					</form>
					<p class="versao-sistema">ⓒ 2022 {translate l="LBL_COPYRIGHT"}<br />{translate l="LBL_DEVELOPED_BY"} Luis Satsuma | v{$ch_ver_org}</p>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="{$app_url}jsManager/Usuarios/login.js?v={$ch_ver}"></script>
</html>