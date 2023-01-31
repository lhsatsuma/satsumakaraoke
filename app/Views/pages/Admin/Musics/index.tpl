{if !$body_only}
{$filter_template}
{/if}
<table class="table table-striped table-list tb-rst-fltr">
	<thead>
		<tr class="d-flex">
			<th scope="col" class="ptr col-2 col-xl-1" dt-h-field="codigo" onclick="OrderByFilter('codigo')">#</th>
			<th scope="col" class="ptr col-2 col-xl-1" dt-h-field="tipo" onclick="OrderByFilter('tipo')">Tipo</th>
			<th scope="col" class="ptr col-8 col-xl-10" dt-h-field="name" onclick="OrderByFilter('name')">Nome</th>
		</tr>
	</thead>
	<tbody>
		{if empty($records)}
		<tr class="ptr r-dt-slct" dt-r-id="{$campos.id}">
			<td colspan="2">Nenhuma música encontrada!</td>
		</tr>
		{else}
		{foreach from=$records item=campos}
		<tr class="ptr r-dt-slct d-flex" dt-r-id="{$campos.id}">
			<th class="col-2 col-xl-1" dt-r-codigo="{$campos.codigo}">{$campos.codigo}</th>
			<td class="col-2 col-xl-1" dt-r-tipo="{$campos.raw.tipo}">{$campos.tipo}</td>
			<td class="col-8 col-xl-10" dt-r-name="{$campos.name}">{$campos.name}</td>
		</tr>
		{/foreach}
		{/if}
	</tbody>
</table>
<table class="table table-striped table-list table-pagination">
	<tbody>
		<tr>
			<td>
				Ir para: <input size="5" type="text" class="form-control QuickGoToPage" inputmode="numeric" pattern="[0-9]*" /> <button type="button" class="btn btn-outline-info btn-rounded" onclick="QuickGoToPage(this)">Ir</button>
			</td>
		</tr>
		<tr>
			<td>{$pagination}</td>
		</tr>
	</tbody>
</table>