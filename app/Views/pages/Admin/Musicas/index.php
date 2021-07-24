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
				Ir para: <input size="5" type="text" class="QuickGoToPage" inputmode="numeric" pattern="[0-9]*" /> <button type="button" class="btn btn-info" onclick="QuickGoToPage(this)">Ir</button>
			</td>
		</tr>
		<tr>
			<td>{$pagination}</td>
		</tr>
	</tbody>
</table>