{if !$bdOnly}
{$filter_template}
{/if}
<div class="table-responsive">
    <table class="table table-striped table-list tb-rst-fltr">
        <thead>
            <tr>
                <th scope="col" class="ptr" dt-h-field="name" onclick="OrderByFiltro('name')">{translate l="LBL_NAME"}</th> 
                <th scope="col" class="ptr" dt-h-field="tabela" onclick="OrderByFiltro('tabela')">{translate l="LBL_RELATED_TABLE"}</th> 
                <th scope="col" class="ptr d-none d-md-table-cell" dt-h-field="registro" onclick="OrderByFiltro('registro')">{translate l="LBL_RELATED_TO"}</th> 
                <th scope="col" class="ptr d-none d-lg-table-cell" dt-h-field="date_modified" onclick="OrderByFiltro('date_modified')">{translate l="LBL_DATE_MODIFIED"}</th> 
            </tr>
        </thead>
        <tbody>
        {if !empty($records)}
            {foreach from=$records item=campos}
                <tr class="ptr row-data-select" dt-r-id="{$campos.id}" onclick="location.href='{$app_url}admin/files/detail/{$campos.id}'" >
                    <td dt-r-name="{$campos.name}" >{$campos.name}</td>
                    <td dt-r-tabela="{$campos.tabela}" >{$campos.tabela}</td>
                    <td class="d-none d-md-table-cell" dt-r-registro_name="{$campos.registro_name}" >{$campos.registro_name}</td>
                    <td class="d-none d-lg-table-cell" dt-r-date_modified="{$campos.date_modified}">{$campos.date_modified}</td>
                </tr>
            {/foreach}
        {else}
        <tr>
            <td colspan="5">Nenhum registro encontrado!</td>
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