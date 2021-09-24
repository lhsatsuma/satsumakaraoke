{if !$bdOnly}
{$filter_template}
{/if}
<table class="table table-responsive-x1 table-striped table-list">
    <thead>
        <tr>
            <th scope="col" class="ptr" dt-h-field="nome" onclick="OrderByFiltro('nome')">Nome</th> 
            <th scope="col" class="ptr" dt-h-field="tabela" onclick="OrderByFiltro('tabela')">Tabela</th> 
            <th scope="col" class="ptr d-none d-md-table-cell" dt-h-field="registro" onclick="OrderByFiltro('registro')">Registro</th> 
            <th scope="col" class="ptr d-none d-lg-table-cell" dt-h-field="data_modificacao" onclick="OrderByFiltro('data_modificacao')">Data Modificação</th> 
        </tr>
    </thead>
    <tbody>
    {if !empty($records)}
        {foreach from=$records item=campos}
            <tr class="ptr row-data-select" dt-r-id="{$campos.id}" onclick="location.href='{$app_url}admin/arquivos/detalhes/{$campos.id}'" >
                <td dt-r-nome="{$campos.nome}" >{$campos.nome}</td>
                <td dt-r-tabela="{$campos.tabela}" >{$campos.tabela}</td>
                <td dt-r-registro_nome="{$campos.registro_nome}" >{$campos.registro_nome}</td>
                <td class="d-none d-lg-table-cell" dt-r-data_modificacao="{$campos.data_modificacao}">{$campos.data_modificacao}</td>
            </tr>
        {/foreach}
    {else}
    <tr>
        <td colspan="5">Nenhum registro encontrado!</td>
    </tr>
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