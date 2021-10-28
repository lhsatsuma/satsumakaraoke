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
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery.min.js?v={$ch_ver}"></script>
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
						{foreach from=$menu_arr item=menu_pai key=key_menu_pai}
							{if $menu_pai.subs}
								<li class="sidebar-dropdown {if $menu_pai.class_active}active{/if}">
								<a id="{$menu_pai.id}" href="#">
									<i class="{$menu_pai.icon}"></i>
									<span>{$menu_pai.lbl}</span>
								</a>
								<div class="sidebar-submenu"> 
									<ul>
										{foreach from=$menu_pai.subs item=menu_filho key=key_menu_filho}
											<li class="{if $menu_filho.class_active}active{/if}" >
												<a id="{$menu_filho.id}" href="{$app_url}{$menu_filho.url}">
												<i class="{$menu_filho.icon}"></i>
												<span>{$menu_filho.lbl}</span>
												</a>
											</li>
										{/foreach}
									</ul>
								</div>
							</li>
							{else}
							<li class="{if $menu_pai.class_active}active{/if}">
								<a id="{$menu_pai.id}" href="{$app_url}{$menu_pai.url}">
									<i class="{$menu_pai.icon}"></i>
									<span>{$menu_pai.lbl}</span>
								</a>
							</li>
							{/if}
						{/foreach}
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
							Versão {$ch_ver_org}
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