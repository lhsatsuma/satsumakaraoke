<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
	<head>
		<title>Error - Satsuma Karaoke</title>
		<style type="text/css">
			<?= preg_replace('#[\r\n\t ]+#', ' ', file_get_contents(ROOTPATH . 'public/cssManager/bootstrap.min.css')) ?>
		</style>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-12 mt-5 text-center">
				<img src="data:image/png;base64, <?php echo base64_encode(file_get_contents(ROOTPATH . 'public/images/logo.png')); ?>" alt="Red dot" />
					<h1 class="mt-3">Ooops!</h1>
					<h3>Parece que houve um problema. Tente novamente mais tarde....</h3>
					<p>Se o erro persistir, contate o administrador.</p>
				</div>
			</div>
		</div>
	</body>
</html>
