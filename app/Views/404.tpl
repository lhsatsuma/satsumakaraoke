<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
	<head>
		<title>404 - {$sys_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/favicon.ico"/>
		<link rel="stylesheet" href="/cssManager/bootstrap.min.css?v=404NotFound">
		<link rel="stylesheet" href="/cssManager/default.css?v=404NotFound">
		{if $auth_user.dark_mode}
			<link rel="stylesheet" href="/cssManager/dark.css?v=404NotFound">
		{/if}
		<script language="javascript" type="text/javascript" src="/jsManager/jquery-3.5.1.min.js?v=404NotFound"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-12 center mt-5">
					<img src="/images/logo.png" />
					<h1>Oops!</h1>
					<h2>Erro 404</h2>
					<h3>A página ou arquivo não foi encontrado.</h3>
					<a href="javascript:void(0)" onclick="window.history.go(-1)" class="btn btn-outline-success btn-rounded">Clique aqui para voltar</a>
				</div>
			</div>
		</div>
	</body>
</html>