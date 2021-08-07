<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=windows-1252">
		<title>Satsuma Karaoke</title>
	</head>
	<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table style="COLOR: #333; font-family: Tahoma, Geneva, sans-serif;" cellpadding="0" cellspacing="0" width="699" align="center" border="0">
			<tbody>
				<tr bgcolor="#791c1e">
					<td height="70" style="text-align:center"><span style="font-size: 16px;font-weight: bold;text-align:center;color: #fff">Esqueci Minha Senha</span></td>
				</tr>
				<tr>
					<td bgcolor="#efefef">
						<table width="618" align="center">
							<tr>
								<td>
									<p>Prezado(a) <b>{$nome}</b>,</p>
									<p>Conforme solicitado em nosso site, <a href="{$app_url}login/fgt_rcv/{$id}/{$hash_esqueci_senha}">clique aqui</a> para recuperar sua senha ou acesse o link abaixo:</p>
									<p><a href="{$app_url}login/fgt_rcv/{$id}/{$hash_esqueci_senha}">{$app_url}login/fgt_rcv/{$id}/{$hash_esqueci_senha}</a></p>
									<p>&nbsp;</p>
									<p>O link acima irá expirar em 24 horas.</p>
									<p>&nbsp;</p>
									<p>Caso você não tenha solicitado sua recuperação de senha, avise o administrador imediatamente. {$app_url}</p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>