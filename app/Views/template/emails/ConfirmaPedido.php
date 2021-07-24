<html>
	<head>
		<meta charset="UTF-8">
		<title>Contmatic Phoenix</title>
		<meta name="Robots" Content="noindex, nofollow">
		<style type="text/css">
			p {
			padding: 0;
			margin: 0;
			}
			dt { font-weight: bold; }
			dd { margin-left: 0; }
			@font-face {
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 400;
			font-display: swap;
			src: local('Roboto'), local('Roboto-Regular'), url(https://fonts.gstatic.com/s/roboto/v20/KFOmCnqEu92Fr1Mu4mxK.woff2) format('woff2');
			unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}
			@font-face {
			font-family: 'Roboto';
			font-style: normal;
			font-weight: 300;
			font-display: swap;
			src: local('Roboto Light'), local('Roboto-Light'), url(https://fonts.gstatic.com/s/roboto/v20/KFOlCnqEu92Fr1MmSU5fBBc4.woff2) format('woff2');
			unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
			}
		</style>
	</head>
	<body bgcolor="#f6f6f6" leftmargin="0" topmargin="0" marginwidth="0">
		<table id="Table_01" width="100%" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4">
			<tr>
				<td>
					<table width="600" align="center" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<span>&nbsp;</span>
							</td>
						</tr>
						<tr>
							<td>
								<span>&nbsp;</span>
							</td>
						</tr>
					</table>
					<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
						<tr>
					</table>
					<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
						<tr>
							<td width="50"></td>
							<td style="font-family: 'Roboto', Arial, sans-serif;">
								<img src="http://contmatic.com.br/macs/imagens/logo-fundacao.png" alt="Fundação Sérgio Contente" width="200" style="display:block; border:0;">
							</td>
							<td width="50"></td>
						</tr>
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
					</table>
					<!-- Fim header -->
					<!-- Content -->
					<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
						<tr>
							<td width="50"></td>
							<td style="font-family: 'Roboto', Arial, sans-serif;">
								<h1 style="font-size: 22pt; color: #222222; line-height: 29px; font-weight: 300; margin: 0;">
									Obrigado pela compra!
								</h1>
							</td>
							<td width="50"></td>
						</tr>
					</table>
					<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
					</table>
					<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
						<tr>
							<td width="50"></td>
							<td style="font-family: 'Roboto', Arial, sans-serif;">
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									Recebemos o seu pedido, muito obrigado por ajudar a Fundação Sérgio Contente
								</p>
								<br>
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									<b>Número do pedido: {$pedido.nome}</b>
								</p>
								<br>
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									<b>Informação sobre o seu pedido:</b>
								</p>
								<br>
								<table cellpadding="0" cellspacing="0"  border="1" style="width: 100%;margin-bottom: 10px;">
									<thead>
										<tr>
											<th style="width: 80%">Modelo</th>
											<th style="width: 10%">Tam.</th>
											<th style="width: 10%">Qtd.</th>
										</tr>
									</thead>
									<tbody>
										{foreach from=$produtos item=produto}
										<tr>
											<td>{$produto.produto}</td>
											<td style="text-align: center">{$produto.tamanho}</td>
											<td style="text-align: center">{$produto.qtd}</td>
										</tr>
										{/foreach}
									</tbody>
									<tfoot>
										<tr>
											<td style="text-align: right">Sub Total:</td>
											<td colspan="2" style="text-align: right">R$ {$pedido.sub_total}</td>
										</tr>
										<tr>
											<td style="text-align: right">Desconto:</td>
											<td colspan="2" style="text-align: right">R$ {$pedido.desconto}</td>
										</tr>
										<tr>
											<td style="text-align: right">Valor Total:</td>
											<th colspan="2" style="text-align: right">R$ {$pedido.valor_total}</th>
										</tr>
									</tfoot>
								</table>
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									<span style="font-weight: 600;">Valor:</span> 01 unidade (R$ 30,00), a partir da 2ª unidade (R$ 25,00 cada)
								</p>
								<br>
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									<span style="font-weight: 600;">Endereço de Retirada:</span> Fundação Sérgio Contente | Rua Tijuco Preto, 269, Tatuapé, São Paulo, SP (Próximo ao Metrô Tatuapé)
								</p>
								<br>
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									<span style="font-weight: 600;"> Horário de Funcionamento:</span> Das 09h30 às 17h30 de segunda à sexta-feira.
								</p>
								<br>
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									<span style="font-weight: 600;">Formas de Pagamento:</span> Aceitamos Cartões de Débito e Crédito, Dinheiro ou se você optar pela entrega via Correio o pagamento poderá ser realizado via PagSeguro.
								</p>
								<br>
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									<span style="font-weight: 600;">Importante:</span> As compras com entrega via correio estão condicionadas ao pagamento da Taxa de Entrega de (R$ 30,00 para lote de até 06 unidades) com envio para Grande São Paulo, Capital e Interior.
								</p>
								<br>
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									Se você optar em receber o seu produto em casa, não esqueça de entrar em contato conosco para efetivarmos a sua compra.
								</p>
								<br>
								<p style="font-size: 13pt; color: #424242; line-height: 29px; font-weight: 300">
									O seu produto será reservado por 15 dias a contar da data da reserva. Após este período, sua reserva será cancelada.
								</p>
								<br>
							</td>
							<td width="50"></td>
						</tr>
						<tr>
							<td colspan="7"><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td colspan="7"><span>&nbsp;</span></td>
						</tr>
					</table>
					<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
						<tr>
							<td width="50"></td>
							<td style="font-family: 'Roboto', Arial, sans-serif;">
								<br><br>
								<p style="color: #424242; line-height: 29px; font-size: 13pt;">
									<b>Em caso de dúvidas ligue para nós (11) 2295.8728 ou envie um e-mail para faleconosco@fundacaosergiocontente.org.br</b>
								</p>
							</td>
							<td width="50"></td>
						</tr>
					</table>
					<!-- Fim content -->
					<!-- Footer -->
					<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
						<tr>
							<td colspan="7"><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td colspan="7"><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td colspan="7"><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td colspan="7"><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td width="300"></td>
							<td width="300"></td>
						</tr>
						<tr>
							<td colspan="7"><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td colspan="7"><span>&nbsp;</span></td>
						</tr>
					</table>
					<table width="350" align="center" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
						<tr>
							<td><span>&nbsp;</span></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>