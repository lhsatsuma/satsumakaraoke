{$filter_template}
<table class="table table-striped table-list">
	<thead>
		<tr class="d-flex">
			<th scope="col" class="pointer col-2 col-xl-1" data-head-field="codigo" onclick="OrderByFiltro('codigo')"># <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer col-2 col-xl-1" data-head-field="tipo" onclick="OrderByFiltro('tipo')">Tipo <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer col-8 col-xl-10" data-head-field="nome" onclick="OrderByFiltro('nome')">Nome <span class="icon-order-by"></span></th>
		</tr>
	</thead>
	<tbody>
		{if empty($records)}
		<tr class="pointer row-data-select" data-row-id="{$campos.id}">
			<td colspan="2">Nenhuma música encontrada!</td>
		</tr>
		{else}
		{foreach from=$records item=campos}
		<tr class="pointer row-data-select d-flex" data-row-id="{$campos.id}">
			<th class="col-2 col-xl-1" data-row-codigo="{$campos.codigo}">{$campos.codigo}</th>
			<td class="col-2 col-xl-1" data-row-tipo="{$campos.raw.tipo}">{$campos.tipo}</td>
			<td class="col-8 col-xl-10" data-row-nome="{$campos.nome}">{$campos.nome}</td>
		</tr>
		{/foreach}
		{/if}
	</tbody>
</table>
<table class="table table-striped table-list table-pagination">
	<tbody>
		<tr>
			<td>
				<p>Ir para página: <input size="5" type="text" class="form-control QuickGoToPage" inputmode="numeric" pattern="[0-9]*" /><button type="button" class="btn btn-info" onclick="QuickGoToPage(this)">Ir</button></p>
			</td>
		</tr>
		<tr>
			<td>{$pagination}</td>
		</tr>
	</tbody>
</table>
<div class="modal fade" id="SelectedRowModal" tabindex="-1" role="dialog" aria-labelledby="SelectedRowModalLabel" aria-hidden="true">
	<input type="hidden" id="IdInsertModal" />
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="SelectedRowModalLabel"></h6>
			</div>
			<div class="modal-body">
				<div class="row margin-5">
					<div class="col-12 primary-row center">
						<button type="button" id="InsertFilaBtn" class="btn btn-success btn-bordered col-8">Colocar na Fila</button>
					</div>
				</div>
				<div class="row margin-5">
					<div class="col-12 primary-row center">
						<button type="button" class="btn btn-danger btn-bordered col-8" data-dismiss="modal">Cancelar</button>
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
						<button class="btn btn-success margin-5" id="ImportMusicaButton">Importar</button>
						<button class="btn btn-info margin-5" id="ImportMusicaAndFilaButton">Importar e colocar na fila</button>
						<button class="btn btn-warning margin-5" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/Musicas/index.js?v={$ch_ver}"></script>