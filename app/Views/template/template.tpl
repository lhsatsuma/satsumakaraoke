{if !$bdOnly}
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
	<head>
		<title>{$title} - Satsuma Karaoke</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/favicon.ico"/>
		
		
		<link rel="stylesheet" href="{$app_url}css/bootstrap.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/fontawesome-all.min.css?v={$ch_ver}">
		{if $auth_user.dark_mode}
			<link rel="stylesheet" href="{$app_url}css/sweetalert2-dark.css?v={$ch_ver}">
			{else}
			<link rel="stylesheet" href="{$app_url}css/sweetalert2.min.css?v={$ch_ver}">
		{/if}
		<link rel="stylesheet" href="{$app_url}css/jquery-ui.structure.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/jquery-ui.theme.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/bootstrap-datetimepicker.css?v={$ch_ver}">
        <link rel="stylesheet" href="{$app_url}css/default.css?v={$ch_ver}">
		{if $auth_user.dark_mode}
       		<link rel="stylesheet" id="darkmodecss" href="{$app_url}css/dark.css?v={$ch_ver}">
		{/if}
		
        <script src="{$app_url}jsManager/fontawesome.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-3.5.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-ui-1.12.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/datepicker-pt-BR.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/sweetalert2.all.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/popper.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/moment-with-locales.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/bootstrap-datetimepicker.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/utils.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/app.js?v={$ch_ver}"></script>
		<script type="text/javascript">
			var app_url = '{$app_url}';
			var ch_ver = '{$ch_ver}';
			var karaoke_url = '{$karaoke_url}';
			var ajax_pagination = parseInt('{$ajax_pagination}');
		</script>
	</head>
	<body>
	<div class="page-wrapper chiller-theme {if !$is_mobile}toggled{/if}">
		<a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
		<i class="fas fa-bars"></i>
		</a>
		<nav id="sidebar" class="sidebar-wrapper">
			<div class="sidebar-content">
				<div class="sidebar-brand">
					<div class="col-10">
						<a href="{$app_url}"><img src="{$app_url}images/logo.png" style="width: 100%" /></a>
					</div>
					<div class="col-2">
						<div id="close-sidebar">
							<i class="fas fa-times"></i>
						</div>
					</div>
				</div>
				<div class="sidebar-menu">
					<ul>
						{if $auth_user.tipo >= 80}
						<li>
							<a href="javascript:void(0)" id="ControleRemotoButton">
							<i class="fas fa-location-arrow"></i>
							<span>Controle</span>
							</a>
						</li>
						{/if}
						<li class="{if $breadcrumb.musicas_fila.index}active{/if}">
							<a href="{$app_url}musicas_fila/index">
							<i class="fas fa-list"></i>
							<span>M??sicas na Fila</span>
							</a>
						</li>
						<li class="{if $breadcrumb.musicas.index}active{/if}">
							<a href="{$app_url}musicas/index">
							<i class="fas fa-music"></i>
							<span>M??sicas</span>
							</a>
						</li>
						<li class="{if $breadcrumb.musicas_fila.topmusicas}active{/if}">
							<a href="{$app_url}musicas_fila/topMusicas">
							<i class="fas fa-music"></i>
							<span>M??sicas mais Tocadas</span>
							</a>
						</li>
						<li class="{if $breadcrumb.usuarios.meusdados}active{/if}">
							<a href="{$app_url}usuarios/MeusDados">
							<i class="fas fa-user"></i>
							<span>Meus Dados</span>
							</a>
						</li>
						{if $auth_user.tipo == 99}
						<li>
							<a href="{$app_url}admin/usuarios/index">
							<i class="fas fa-users"></i>
							<span>Administra????o</span>
							</a>
						</li>
						{/if}
						<li class="dark-mode-li">
							<a href="javascript:void(0)">
							<i class="fas fa-moon"></i> <span>Tema escuro</span>
							<label class="switch">
								<input type="checkbox" {if $auth_user.dark_mode}checked{/if} onclick="toggleDarkMode()">
								<span class="slider round"></span>
							</label>
							</a>
						</li>
						<li>
							<a href="{$app_url}login/logout">
							<i class="fas fa-power-off"></i>
							<span>Sair</span>
							</a>
						</li>
					</ul>
				</div>
				<!-- sidebar-menu  -->
			</div>
		</nav>
		<!-- sidebar-wrapper  -->
		<main class="page-content">
			<div class="container-fluid primary-container">
				<div class="box box-primary">
					<div class="box-body">
						<div class="row">
							<div class="col-12 div-title">
								<h4>{$title}</h4>
							</div>
						</div>
						{$content}
					</div>
				</div>
		</main>
		<!-- page-content" -->
		</div>
		<!-- page-wrapper -->
		{if $auth_user.tipo >= 80}
		<div class="modal fade" id="ControleRemotoModal" tabindex="-1" role="dialog" aria-labelledby="ControleRemotoModalLabel" aria-hidden="true">
			<input type="hidden" id="IdInsertModal" />
			<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h6 class="modal-title" id="ControleRemotoModalLabel">Controle Remoto</h6>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-6 center">
								<span class="ptr controlbtns" data-val="play">
									<i class="fas fa-play margin-5"></i>
									<p>Play</p>
								</span>
							</div>
							<div class="col-6 center">
								<span class="ptr controlbtns" data-val="pause">
									<i class="fas fa-pause margin-5"></i>
									<p>Pausar</p>
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col-6 center">
								<span class="ptr controlbtns" data-val="next">
									<i class="fas fa-step-forward margin-5"></i>
									<p>Pr??ximo</p>
								</span>
							</div>
							<div class="col-6 center">
								<span class="ptr controlbtns" data-val="repeat">
									<i class="fas fa-sync-alt margin-5"></i>
									<p>Repetir</p>
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col-12 center mb-4">
								<span class="ptr">
									<p id="volP">100%</p>
									<input type="range" class="form-control-range" id="volumeRange" step="5">
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col-12 center">
								<span class="ptr controlbtns" data-val="mute">
									<i class="fas fa-volume-mute margin-5"></i>
									<p>Mutar</p>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{/if}
	</body>
	<script type="text/javascript" src="{$app_url}jsManager/ControleRemoto.js?v={$ch_ver}"></script>
</html>
{else}
{$content}
{/if}