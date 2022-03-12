{if !$bdOnly}
{$filter_template}
{/if}
<div class="table-responsive">
	<table class="table table-striped table-list tb-rst-fltr">
		<thead>
			<tr class="d-flex">
				<th scope="col" class="ptr col-3 col-xl-1" dt-h-field="tipo" onclick="OrderByFiltro('tipo')">Idioma</th>
				<th scope="col" class="ptr col-9 col-xl-11" dt-h-field="name" onclick="OrderByFiltro('name')">Nome</th>
			</tr>
		</thead>
		<tbody>
			{if empty($records)}
			<tr class="ptr r-dt-slct" dt-r-id="{$campos.id}">
				<td colspan="2">Nenhuma música encontrada!</td>
			</tr>
			{else}
			{foreach from=$records item=campos}
			<tr class="ptr r-dt-slct d-flex" dt-r-id="{$campos.id}" dt-r-fvt="{$campos.favorite}">
				<td class="col-3 col-xl-1" dt-r-tipo="{$campos.raw.tipo}">{$campos.tipo}</td>
				<td class="col-9 col-xl-11" dt-r-name="{$campos.name}"  dt-r-codigo="{$campos.codigo}">[{$campos.codigo}] {$campos.name}</td>
			</tr>
			{/foreach}
			{/if}
		</tbody>
	</table>
</div>
<table class="table table-striped table-list table-pagination">
	<tbody>
		<tr>
			<td>
				<p>Ir para página: <input size="5" type="text" class="form-control QuickGoToPage" inputmode="numeric" pattern="[0-9]*" /><button type="button" class="btn btn-outline-info btn-rounded" onclick="QuickGoToPage(this)">Ir</button></p>
			</td>
		</tr>
		<tr>
			<td>{$pagination}</td>
		</tr>
	</tbody>
</table>
{if !$bdOnly}
<div class="modal fade" id="SelectedRowModal" tabindex="-1" role="dialog" aria-labelledby="SelectedRowModalLabel" aria-hidden="true">
	<input type="hidden" id="IdInsertModal" />
	<input type="hidden" id="itsFavorite" />
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="SelectedRowModalLabel"></h6>
			</div>
			<div class="modal-body">
				<div class="row margin-5">
					<div class="col-12 primary-row center">
						<button type="button" id="InsertFilaBtn" class="btn btn-outline-success btn-rounded btn-bordered col-8">Colocar na Fila</button>
					</div>
					<div class="col-12 primary-row center">
						<button type="button" id="InsertFavoriteBtn" class="btn btn-outline-info btn-rounded btn-bordered col-8"><i class="fas fa-star"></i> Favoritar</button>
					</div>
				</div>
				<div class="row margin-5">
					<div class="col-12 primary-row center">
						<button type="button" class="btn btn-outline-danger btn-bordered col-8" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="ImportModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="ImportModalLabel">Importar Música do Youtube</h6>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 margin-b-10">
						<label for="ImportModalLink">Link Youtube:</label>
						<input class="form-control" type="text" id="ImportModalLink" name="ImportModalLink" autocomplete="off" />
					</div>
					<div class="col-12 margin-b-10" id="ImportModalLinkTitleDiv"></div>
					<div class="col-12">
						<button class="btn btn-outline-success btn-rounded margin-5" id="ImportMusicaButton">Importar</button>
						<button class="btn btn-outline-info btn-rounded margin-5" id="ImportMusicaAndFilaButton">Importar e colocar na fila</button>
						<button class="btn btn-outline-warning btn-rounded margin-5" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="helpSongsModal" tabindex="-1" role="dialog" aria-labelledby="helpSongsModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="helpSongsModalLabel">Ajuda sobre as músicas</h6>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 margin-b-10">
						<label for="ImportModalLink">Como funciona?</label>
						<ul>
							<li>Você pode buscar músicas através da caixa geral pesquisando por name, cantor, codigo ou idioma.</li>
							<li>Caso queira cantar uma música, clique sobre ela, e confirme se você deseja colocar na fila.</li>
							<li>Automaticamente sua música estará na fila e você poderá acompanhar através da tela principal ou pelo menu "Músicas na Fila".</li>
						</ul>
					</div>
					<div class="col-12 margin-b-10">
						<label for="ImportModalLink">Quero cantar uma música que não existe na lista</label>
						<ul>
							<li>Todos os vídeos são importados do YouTube.</li>
							<li>Você pode pedir para um dos colaboradores para verificar se existe um vídeo disponível para importação.</li>
						</ul>
					</div>
					<div class="col-12 margin-b-10">
						<label for="ImportModalLink">Músicas favoritas</label>
						<ul>
							<li>Ao clicar em cima de uma música, você pode colocá-la nos favoritos ou removê-la.</li>
							<li>A música favoritada estará disponível para busca através do botão de busca avançada.</li>
						</ul>
					</div>
					<div class="col-12 margin-b-10">
						<label for="ImportModalLink">Direitos Autorais</label>
						<ul>
							<li>Todos os direitos autorais das músicas são mantidos pelos próprios criadores que podem ser encontrados através do vídeo original no YouTube.</li>
							<li>Todos os videos foram baixados do YouTube, caso você seja o proprietário de um deles e queira a remoção do nosso sistema, entre em contato com o administrador.</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-info btn-rounded" data-dismiss="modal">Fechar</button>
				{if $showPopupWizard}<button type="button" class="btn btn-outline-warning btn-rounded" onclick="hideAlwaysPopupWizard()">Não mostrar novamente</button>{/if}
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/Musicas/index.js?v={$ch_ver}"></script>
{/if}
{if !$bdOnly AND $showPopupWizard}
	<script type="text/javascript">$('#helpSongsModal').modal('show');</script>
{/if}