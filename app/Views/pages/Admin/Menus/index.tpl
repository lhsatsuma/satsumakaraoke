{if !$bdOnly}
{$filter_template}
{/if}
<div class="table-responsive">
	<table class="table table-striped table-list tb-rst-fltr">
		<thead>
			<tr>
				<th scope="col" class="ptr" dt-h-field="ativo" onclick="OrderByFilter('ativo')">{translate l="LBL_ACTIVE"}</th>
				<th scope="col" class="ptr" dt-h-field="ordem" onclick="OrderByFilter('ordem')">{translate l="LBL_ORDER"}</th>
				<th scope="col" class="ptr" dt-h-field="tipo" onclick="OrderByFilter('tipo')">{translate l="LBL_TYPE"}</th>
				<th scope="col" class="ptr" dt-h-field="name" onclick="OrderByFilter('name')">{translate l="LBL_NAME"}</th>
				<th scope="col" class="ptr d-none d-xl-table-cell" dt-h-field="date_created" onclick="OrderByFilter('date_created')">{translate l="LBL_DATE_CREATED"}</th>
				<th scope="col" class="ptr d-none d-lg-table-cell" dt-h-field="date_modified" onclick="OrderByFilter('date_modified')">{translate l="LBL_DATE_MODIFIED"}</th>
			</tr>
		</thead>
		<tbody>
		{if !empty($records)}
			{foreach from=$records item=campos}
				<tr class="ptr r-dt-slct" dt-r-id="{$campos.id}" onclick="location.href='{$app_url}admin/menus/detail/{$campos.id}'">
					<td dt-r-ativo="{$campos.ativo}">{$campos.ativo}</td>
					<td dt-r-ordem="{$campos.ordem}">{$campos.ordem}</td>
					<td dt-r-tipo="{$campos.tipo}">{$campos.tipo}</td>
					<td dt-r-name="{$campos.name}">{$campos.name}</td>
					<td class="d-none d-xl-table-cell" dt-r-date_created="{$campos.date_created}">{$campos.date_created}</td>
					<td class="d-none d-lg-table-cell" dt-r-date_modified="{$campos.date_modified}">{$campos.date_modified}</td>
				</tr>			
			{/foreach}
			
		{else}
		<tr>
			<td colspan="5">{translate l="LBL_NO_RECORDS_FOUND"}</td>
		</tr>	
		{/if}
		</tbody>
	</table>
</div>
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