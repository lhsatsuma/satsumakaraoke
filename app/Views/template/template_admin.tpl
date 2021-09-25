{if !$bdOnly}
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>{$title} - {$sys_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<meta charset=UTF-8>
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/favicon.ico"/>
		<link rel="stylesheet" href="{$app_url}cssManager/bootstrap.min.css?v={$ch_ver}" type="text/css" media="screen" lazyload="1">
		<link rel="stylesheet" href="{$app_url}cssManager/fontawesome-all.min.css?v={$ch_ver}" type="text/css" media="screen" lazyload="1">
		<link rel="stylesheet" href="{$app_url}cssManager/sweetalert2.min.css?v={$ch_ver}" type="text/css" media="screen" lazyload="1">
		<link rel="stylesheet" href="{$app_url}cssManager/jquery-ui.structure.min.css?v={$ch_ver}" type="text/css" media="screen" lazyload="1">
		<link rel="stylesheet" href="{$app_url}cssManager/jquery-ui.theme.min.css?v={$ch_ver}" type="text/css" media="screen" lazyload="1">
		<link rel="stylesheet" href="{$app_url}cssManager/bootstrap-datetimepicker.css?v={$ch_ver}" type="text/css" media="screen" lazyload="1">
        <link rel="stylesheet" href="{$app_url}cssManager/default.css?v={$ch_ver}" type="text/css" media="screen" lazyload="1">
		{if $auth_user.dark_mode}
       		<link rel="stylesheet" id="darkmodecss" href="{$app_url}cssManager/dark.css?v={$ch_ver}" type="text/css" media="screen" lazyload="1">
		{/if}
        <script language="javascript" type="text/javascript">
			localStorage.dark_mode_active = {$auth_user.dark_mode};
		</script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-3.5.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-ui-1.12.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/sweetalert2.all.min.js?v={$ch_ver}"></script>
		<script src="https://cdn.tiny.cloud/1/gkma8l0v7mxj6bd7bono39z4l7bzq7k29vt4yp3ja81e67db/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
		<script type="text/javascript">var _app_vars = {$JS_VARS};</script>
		<script type="text/javascript" src="{$app_url}jsManager/utils.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/app.js?v={$ch_ver}"></script>
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
						<li>
							<a href="{$app_url}home/index">
							<i class="fas fa-globe"></i>
							<span>Ir para o Site</span>
							</a>
						</li>
						{if $perms.cod_1002.r}
							<li class="{if $breadcrumb.admin.musicas.karaoke}active{/if}">
								<a href="{$app_url}admin/karaoke/index" target="_blank">
								<i class="fas fa-music"></i>
								<span>Karaokê</span>
								</a>
							</li>
						{/if}
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
						{if $perms.cod_7.r OR $perms.cod_1.r OR $perms.cod_2.r OR $perms.cod_3.r}
							<li class="sidebar-dropdown {if $breadcrumb.admin.usuarios OR $breadcrumb.admin.grupos OR $breadcrumb.admin.permissao OR $breadcrumb.admin.arquivos}active{/if}">
								<a href="#">
									<i class="fas fa-list"></i>
									<span>Cadastros</span>
								</a>
								<div class="sidebar-submenu"> 
									<ul>
										{if $perms.cod_7.r}
										<li class="{if $breadcrumb.admin.arquivos}active{/if}" >
											<a href="{$app_url}admin/arquivos/index">
											<i class="fas fa-users"></i>
											<span>Arquivos</span>
											</a>
										</li>
										{/if}
										{if $perms.cod_1.r}
										<li class="{if $breadcrumb.admin.usuarios}active{/if}" >
											<a href="{$app_url}admin/usuarios/index">
											<i class="fas fa-users"></i>
											<span>Usuários</span>
											</a>
										</li>
										{/if}
										{if $perms.cod_2.r}
											<li class="{if $breadcrumb.admin.grupos}active{/if}" >
												<a href="{$app_url}admin/grupos/index">
												<i class="fas fa-users-cog"></i>
												<span>Grupos</span>
												</a>
											</li>
										{/if}
										{if $perms.cod_3.r}
										<li class="{if $breadcrumb.admin.permissao}active{/if}" >
											<a href="{$app_url}admin/permissao/index">
											<i class="fas fa-users-cog"></i>
											<span>Permissão</span>
											</a>
										</li>
										{/if}
									</ul>
								</div>
							</li>
						{/if}
						{if $perms.cod_4.r OR $perms.cod_6.r OR $perms.cod_8.r}
							<li class="sidebar-dropdown {if $breadcrumb.admin.internal OR $breadcrumb.admin.permissao_grupo OR $breadcrumb.admin.parametros}active{/if}">
								<a href="#">
									<i class="fas fa-list"></i>
									<span>Administração</span>
								</a>
								<div class="sidebar-submenu"> 
									<ul>
										{if $perms.cod_4.r}
										<li class="{if $breadcrumb.admin.parametros}active{/if}" >
											<a href="{$app_url}admin/parametros/index">
											<i class="fas fa-users-cog"></i>
											<span>Parâmetros do Sistema</span>
											</a>
										</li>
										{/if}
										{if $perms.cod_4.r}
										<li class="{if $breadcrumb.admin.permissao_grupo}active{/if}" >
											<a href="{$app_url}admin/permissao_grupo/index">
											<i class="fas fa-users-cog"></i>
											<span>Permissões do Grupo</span>
											</a>
										</li>
										{/if}
										{if $perms.cod_6.r}
											<li class="{if $breadcrumb.admin.internal}active{/if}" >
												<a href="{$app_url}admin/internal/index">
												<i class="fas fa-list"></i>
												<span>Utilidades</span>
												</a>
											</li>
										{/if}
									</ul>
								</div>
							</li>
						{/if}
						<li class="dark-mode-li">
							<a href="javascript:void(0)">
							<i class="fas fa-moon"></i> <span>Tema escuro</span>
							<label class="switch-dark-mode">
								<input type="checkbox" {if $auth_user.dark_mode}checked{/if} onclick="toggleDarkMode()">
								<span class="slider-dark-mode round-dark-mode"></span>
							</label>
							</a>
						</li>
						<li>
							<a href="{$app_url}admin/login/logout">
							<i class="fas fa-power-off"></i>
							<span>Sair</span>
							</a>
						</li>
						<li class="versao-sistema">
							Versão {$app_ver} | {$ch_ver_org}
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
		</body>
</html>
{else}
{$content}
{/if}