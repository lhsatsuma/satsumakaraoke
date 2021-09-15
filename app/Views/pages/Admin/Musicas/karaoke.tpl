<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ptbr" lang="ptbr">
	<head>
		<title>Satsuma Karaoke</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/favicon.ico"/>
		<link rel="stylesheet" href="{$app_url}cssManager/bootstrap.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}cssManager/default.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}cssManager/fontawesome-all.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}cssManager/sweetalert2.min.css?v={$ch_ver}">
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-3.5.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-ui-1.12.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/sweetalert2.all.min.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/app.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/utils.js?v={$ch_ver}"></script>
		<script type="text/javascript">
			var _app_vars = {$JS_VARS};
			var karaokeURL = '{$karaokeURL}';
			var host_fila = '{$host_fila}';
		</script>
	</head>
	<body id="bodyKaraoke" style="color: white;background: url('{$app_url}images/karaoke_bg.gif')">
		<div class="modal fade" id="InitialModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="InitialModalLabel" aria-hidden="true">
			<input type="hidden" id="IdInsertModal" />
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h6 class="modal-title" id="InitialModalLabel" style="color: black">Escolha o tipo de tela</h6>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-12 primary-row center">
							<p><button class="btn btn-success" onclick="removeInitial()" >TELA COMPLETA</button></p>
							<p><button class="btn btn-success" onclick="removeInitialOnlyFila()" >APENAS AS MUSICAS NA FILA</button></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="bdKaraoke" class="row m-0"></div>
	</body>
	<script type="text/javascript" src="{$app_url}jsManager/Admin/Musicas/karaoke.js?v={$ch_ver}"></script>
</html>