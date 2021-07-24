{$filter_template}
<table class="table table-responsive-xl table-striped table-list">
	<thead>
		<tr>
			<th scope="col" class="pointer" data-head-field="tipo" onclick="OrderByFiltro('tipo')">Tipo <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" data-head-field="nome" onclick="OrderByFiltro('nome')">Nome <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer d-none d-md-table-cell" data-head-field="email" onclick="OrderByFiltro('email')">Email <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" data-head-field="status" onclick="OrderByFiltro('status')">Status <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer d-none d-xl-table-cell" data-head-field="data_criacao" onclick="OrderByFiltro('data_criacao')">Data Criação <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer d-none d-lg-table-cell" data-head-field="data_modificacao" onclick="OrderByFiltro('data_modificacao')">Data Modificação <span class="icon-order-by"></span></th>
		</tr>
	</thead>
	<tbody>
	{if !empty($records)}
		{foreach from=$records item=campos}
			<tr class="pointer row-data-select" data-row-id="{$campos.id}" onclick="location.href='{$app_url}admin/usuarios/detalhes/{$campos.id}'">
				<td data-row-tipo="{$campos.tipo}">{$campos.tipo}</td>
				<td data-row-nome="{$campos.nome}">{$campos.nome}</td>
				<td class="d-none d-md-table-cell" data-row-email="{$campos.email}"> {$campos.email} </td>
				<td data-row-status="{$campos.status}">{$campos.status}</td>
				<td class="d-none d-xl-table-cell" data-row-data_criacao="{$campos.data_criacao}">{$campos.data_criacao}</td>
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
<script type="text/javascript" src="{$app_url}jsManager/Musicas_fila/index.js?v={$ch_ver}"></script>