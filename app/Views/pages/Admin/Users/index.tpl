{if !$bdOnly}
{$filter_template}
{/if}
<div class="table-responsive">
	<table class="table table-striped table-list tb-rst-fltr">
		<thead>
			<tr>
				<th scope="col" class="ptr" dt-h-field="name" onclick="OrderByFilter('name')">{translate l="LBL_NAME"}</th>
				<th scope="col" class="ptr d-none d-md-table-cell" dt-h-field="email" onclick="OrderByFilter('email')">{translate l="LBL_EMAIL"}</th>
				<th scope="col" class="ptr" dt-h-field="status" onclick="OrderByFilter('status')">{translate l="LBL_STATUS"}</th>
				<th scope="col" class="ptr d-none d-xl-table-cell" dt-h-field="date_created" onclick="OrderByFilter('date_created')">{translate l="LBL_DATE_CREATED"}</th>
				<th scope="col" class="ptr d-none d-lg-table-cell" dt-h-field="date_modified" onclick="OrderByFilter('date_modified')">{translate l="LBL_DATE_MODIFIED"}</th>
			</tr>
		</thead>
		<tbody>
		{if !empty($records)}
			{foreach from=$records item=campos}
				<tr class="ptr r-dt-slct" dt-r-id="{$campos.id}" onclick="location.href='{$app_url}admin/users/detail/{$campos.id}'">
					<td dt-r-name="{$campos.name}">{$campos.name}</td>
					<td class="d-none d-md-table-cell" dt-r-email="{$campos.email}"> {$campos.email} </td>
					<td dt-r-status="{$campos.status}">{$campos.status}</td>
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