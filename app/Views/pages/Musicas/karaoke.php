<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ptbr" lang="ptbr">
	<head>
		<title>Satsuma Karaoke</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="initial-scale=1">
		<link rel="shortcut icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$app_url}images/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="images/favicon.ico"/>
		<link rel="stylesheet" href="{$app_url}css/bootstrap.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/default.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/fontawesome-all.min.css?v={$ch_ver}">
		<link rel="stylesheet" href="{$app_url}css/sweetalert2.min.css?v={$ch_ver}">
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-3.5.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/jquery-ui-1.12.1.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/bootstrap.min.js?v={$ch_ver}"></script>
		<script language="javascript" type="text/javascript" src="{$app_url}jsManager/sweetalert2.all.min.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/app.js?v={$ch_ver}"></script>
		<script type="text/javascript" src="{$app_url}jsManager/utils.js?v={$ch_ver}"></script>
		<script type="text/javascript">
			var app_url = '{$app_url}';
			var karaokeURL = '{$karaokeURL}';
		</script>
	</head>
	<body id="bodyKaraoke" style="color: white;background: url('{$app_url}images/karaoke_bg.gif')">
		<div class="modal fade" id="InitialModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="InitialModalLabel" aria-hidden="true">
			<input type="hidden" id="IdInsertModal" />
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h6 class="modal-title" id="InitialModalLabel" style="color: black">Proteção anti-BOT</h6>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-12 primary-row center">
							<button class="btn btn-success" onclick="removeInitial()" >CLIQUE EM MIM</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row m-0">
			<div class="col-4">
				<div class="row">
					<div class="col-12 mt-2 mb-2 center">
						<img src="{$app_url}images/logo.png" style="width: 65%"/>
					</div>
				</div>
				<div class="row" id="SongLists">
					<div class="col-12 center">
						<p><strong>Músicas na Fila:</strong></p>
					</div>
					<div class="col-12" id="SongListsDiv"></div>
				</div>
			</div>
			<div class="col-8">
				<div class="row">
					<div class="col-12 center playingNowDiv" style="padding: 30px 0px 15px 0px">
						<h4><span id="playingNow"></span></h4>
						<div id="pausedDiv" class="center" style="display: none">
							<h3>VIDEO PAUSADO!</h3>
						</div>
					</div>
					<div class="col-12 center videoDiv">
						<input type="hidden" id="songNowId" value=""/>
						<video id="video" autoplay>
							<source id="videoSrc" src="" type="video/mp4" />
						</video>
					</div>
					<div class="col-12 center volumeDiv">
						<h3>Volume: <span id="volumeSpan">100%</span></h3>
					</div>
				</div>
			</div>
			<div id="joinUsKaraoke" class="col-12 center">
				<h3>Junte-se a nós no karaokê! Acesse <span class="b800">{$host_fila}</span> e coloque suas músicas na fila para cantar!</h3>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="{$app_url}jsManager/Musicas/karaoke.js?v={$ch_ver}"></script>
</html>