{$filter_template}
<table class="table table-responsive-xl table-striped table-list">
	<thead>
		<tr>
			<th scope="col" class="pointer" dt-h-field="codigo" onclick="OrderByFiltro('codigo')">Código <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" dt-h-field="nome" onclick="OrderByFiltro('nome')">Nome <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" dt-h-field="tipo" onclick="OrderByFiltro('tipo')">Tipo <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" dt-h-field="data_criacao" onclick="OrderByFiltro('data_criacao')">Data Criação <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" dt-h-field="md5" onclick="OrderByFiltro('md5')">MD5 <span class="icon-order-by"></span></th>
			<th scope="col">Deletar</th>
		</tr>
	</thead>
	<tbody>
		{if empty($records)}
		<tr class="pointer row-data-select" dt-r-id="{$campos.id}">
			<td colspan="4">Nenhuma música encontrada!</td>
		</tr>
		{else}
		{foreach from=$records item=campos}
		<tr class="row-data-select" dt-r-id="{$campos.id}">
			<td class="pointer" onclick="changeNameTo(this)" scope="row" dt-r-codigo="{$campos.codigo}">{$campos.codigo}</td>
			<td class="pointer" onclick="changeNameTo(this)" dt-r-nome="{$campos.nome|escape:'url'}">{$campos.nome}</td>
			<td class="pointer" onclick="changeNameTo(this)" dt-r-tipo="{$campos.raw.tipo}">{$campos.tipo}</td>
			<td class="pointer" onclick="changeNameTo(this)" dt-r-data_criacao="{$campos.data_criacao}">{$campos.data_criacao}</td>
			<td class="pointer" dt-r-md5="{$campos.md5}">{$campos.md5}</td>
			<td><i class="fas fa-trash pointer" onclick="changeNameToDel(this)"></i></td>
		</tr>
		{/foreach}
		{/if}
	</tbody>
</table>
<table class="table table-striped table-list table-pagination">
	<tbody>
		<tr>
			<td>
				Ir para: <input size="5" type="text" class="QuickGoToPage" inputmode="numeric" pattern="[0-9]*" /> <button type="button" class="btn btn-info" onclick="QuickGoToPage(this)">Ir</button>
			</td>
		</tr>
		<tr>
			<td>{$pagination}</td>
		</tr>
	</tbody>
</table>
<div class="modal fade" id="ChangeNameToModal" tabindex="-1" role="dialog" aria-labelledby="ChangeNameToModalLabel" aria-hidden="true">
	<input type="hidden" id="IdInsertModal" />
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="ChangeNameToModalLabel">Mudar nome da Música</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="changeRowID" value=""/>
				<div class="row">
					<div class="col-12">
						<p>Atual: <input class="form-control" type="text" id="changeRowOldNome" value="" disabled /></p>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<p>Novo: <input class="form-control" type="text" id="changeRowNewNome" value=""/></p>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<p>Tipo: <select class="form-control" id="changeRowTipo"><option value="N/A">N/A</option><option value="INT">INT</option><option value="BRL">BRL</option><option value="ESP">ESP</option><option value="JPN">JPN</option><option value="OTR">OTR</option></select></p>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<p><button type="button" class="btn btn-info" id="changeByTraco">Inverter Titulo/cantor</button></p>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<button class="btn btn-success" type="button" onclick="saveChangeName(this)"><img class="loading-icon" src="{$app_url}images/loading.gif" /> <i class="fas fa-save"></i> Salvar</button></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/Admin/Musicas/fixNomes.js?v={$ch_ver}"></script>