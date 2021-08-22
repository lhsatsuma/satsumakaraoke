{if !$bdOnly}
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
	<head>
		<title>{$title} - Satsuma Karaokê</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/favicon.ico"/>
		<link rel="stylesheet" href="{$app_url}css/bootstrap.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/fontawesome-all.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/sweetalert2.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/jquery-ui.structure.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/jquery-ui.theme.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/default.css?v={$ch_ver}">
		{if $auth_user.dark_mode}
       		<link rel="stylesheet" href="{$app_url}css/dark.css?v={$ch_ver}">
		{/if}
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-3.5.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-ui-1.12.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/sweetalert2.all.min.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/utils.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/app.js?v={$ch_ver}"></script>
		<script type="text/javascript">
			var app_url = '{$app_url}';
			var karaoke_url = '{$karaoke_url}';
			var ajax_pagination = parseInt('{$ajax_pagination}');
		</script>
	</head>
	<body>
		<div class="page-wrapper chiller-theme toggled">
		<a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
		<i class="fas fa-bars"></i>
		</a>
		<nav id="sidebar" class="sidebar-wrapper">
			<div class="sidebar-content">
				<div class="sidebar-brand">
					<div class="col-10">
						<img src="{$app_url}images/logo.png" style="width: 100%" />
					</div>
					<div class="col-2">
						<div id="close-sidebar">
							<i class="fas fa-times"></i>
						</div>
					</div>
				</div>
				<div class="sidebar-menu">
					<ul>
						<li>
							<a href="{$app_url}home/index">
							<i class="fas fa-globe"></i>
							<span>Ir para o Site</span>
							</a>
						</li>
						<li class="{if $breadcrumb.admin.musicas.karaoke}active{/if}">
							<a href="{$app_url}admin/musicas/karaoke" target="_blank">
							<i class="fas fa-music"></i>
							<span>Karaokê</span>
							</a>
						</li>
						<li class="{if $breadcrumb.admin.musicas_fila}active{/if}">
							<a href="{$app_url}admin/musicas_fila/index">
							<i class="fas fa-list"></i>
							<span>Músicas na Fila</span>
							</a>
						</li>
						<li class="sidebar-dropdown {if $breadcrumb.admin.musicas}active{/if}">
							<a href="#">
								<i class="fas fa-music"></i>
								<span>Músicas</span>
							</a>
							<div class="sidebar-submenu" {if $breadcrumb.admin.musicas} style="display: block"{/if}> 
								<ul>
									<li class="{if $breadcrumb.admin.musicas.index}active{/if}" >
										<a href="{$app_url}admin/musicas/index">
										<i class="fas fa-list"></i>
										<span>Listar Músicas</span>
										</a>
									</li>
									<li class="{if $breadcrumb.admin.musicas.import}active{/if}" >
										<a href="{$app_url}admin/musicas/import">
										<i class="fas fa-list"></i>
										<span>Importar Músicas</span>
										</a>
									</li>
									<li class="{if $breadcrumb.admin.musicas.fixNomes}active{/if}" >
										<a href="{$app_url}admin/musicas/fixNomes">
										<i class="fas fa-list"></i>
										<span>Arrumar Nomes Músicas</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						{if $auth_user_admin.tipo == 99 OR $auth_user.tipo == 99}
							<li class="sidebar-dropdown {if $breadcrumb.admin.usuarios OR $breadcrumb.admin.internal}active{/if}">
								<a href="#">
									<i class="fas fa-list"></i>
									<span>Administração</span>
								</a>
								<div class="sidebar-submenu"> 
									<ul>
										<li class="{if $breadcrumb.admin.usuarios}active{/if}" >
											<a href="{$app_url}admin/usuarios/index">
											<i class="fas fa-users"></i>
											<span>Usuários</span>
											</a>
										</li>
										<li class="{if $breadcrumb.admin.internal}active{/if}" >
											<a href="{$app_url}admin/internal/index">
											<i class="fas fa-list"></i>
											<span>Utilidades</span>
											</a>
										</li>
									</ul>
								</div>
							</li>
						{/if}
						<li>
							<a href="{$app_url}admin/login/logout">
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
				<div class="row">
					<div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 primary-row">
						<div id="header_logo"><img src="{$app_url}images/logo.png" /></div>
					</div>
				</div>
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
		</body>
</html>
{else}
{$content}
{/if}