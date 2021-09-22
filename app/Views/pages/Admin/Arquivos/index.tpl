{$filter_template}
{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<table class="table table-responsive-x1 table-striped table-list">
    <thead>
        <tr>
          <th scope="col" class="pointer d-none d-md-table-cell" data-head-field="nome" onclick="OrderByFiltro('nome')">Nome <span class="icon-order-by"></span></th> 
          <th scope="col" class="pointer d-none d-md-table-cell" data-head-field="tabela" onclick="OrderByFiltro('tabela')">Tabela <span class="icon-order-by"></span></th> 
          <th scope="col" class="pointer d-none d-md-table-cell" data-head-field="registro" onclick="OrderByFiltro('registro')">Registro <span class="icon-order-by"></span></th> 
          <th scope="col" class="pointer d-none d-lg-table-cell" data-head-field="data_modificacao" onclick="OrderByFiltro('data_modificacao')">Data Modificação<span class="icon-order-by"></span> </th> 
        </tr>
    </thead>
    <tbody>
    {if !empty($records)}
        {foreach from=$records item=campos}
            <tr class="pointer row-data-select" data-row-id="{$campos.id}" onclick="location.href='{$app_url}admin/arquivos/detalhes/{$campos.id}'" >
                <td data-row-nome="{$campos.nome}" >{$campos.nome}</td>
                <td data-row-tabela="{$campos.tabela}" >{$campos.tabela}</td>
                <td data-row-registro_nome="{$campos.registro_nome}" >{$campos.registro_nome}</td>
                <td class="d-none d-lg-table-cell" data-row-data_modificacao="{$campos.data_modificacao}">{$campos.data_modificacao}</td>
            </tr>
        {/foreach}
    {else}
    <tr>
        <td colspan="5">Nenhum registro encontrado!</td>
    </tr>
    {/if}
    </tbody>
</table>
{if $pagination}
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
{/if}