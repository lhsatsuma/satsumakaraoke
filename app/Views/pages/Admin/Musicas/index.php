{if !$body_only}
{$filter_template}
{/if}
<table class="table table-striped table-list table-result-filter">
	<thead>
		<tr class="d-flex">
			<th scope="col" class="pointer col-2 col-xl-1" dt-h-field="codigo" onclick="OrderByFiltro('codigo')"># <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer col-2 col-xl-1" dt-h-field="tipo" onclick="OrderByFiltro('tipo')">Tipo <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer col-8 col-xl-10" dt-h-field="nome" onclick="OrderByFiltro('nome')">Nome <span class="icon-order-by"></span></th>
		</tr>
	</thead>
	<tbody>
		{if empty($records)}
		<tr class="pointer row-data-select" dt-r-id="{$campos.id}">
			<td colspan="2">Nenhuma música encontrada!</td>
		</tr>
		{else}
		{foreach from=$records item=campos}
		<tr class="pointer row-data-select d-flex" dt-r-id="{$campos.id}">
			<th class="col-2 col-xl-1" dt-r-codigo="{$campos.codigo}">{$campos.codigo}</th>
			<td class="col-2 col-xl-1" dt-r-tipo="{$campos.raw.tipo}">{$campos.tipo}</td>
			<td class="col-8 col-xl-10" dt-r-nome="{$campos.nome}">{$campos.nome}</td>
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