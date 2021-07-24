<form method="post" id="filtroForm" action="{$app_url}musicas_fila/index">
	<input type="hidden" id="search_usuario_cantar" name="search_usuario_cantar" value="{$search_usuario_cantar}"/>
	<input type="hidden" id="search_status" name="search_status" value="{$search_status}"/>
	<div class="row">
		<div class="col-12">
			<p><button class="btn btn-{$color_usuario_cantar} search-button" id="SearchByAssigned">Filtrar por Meu Nome {$icon_usuario_cantar}</button>
				<button class="btn btn-{$color_status_pendente} search-button" id="SearchByPendente">Filtrar pelos Pendentes {$icon_status_pendente}</button>
			</p>
		</div>
	</div>
	<input type="hidden" name="order_by_field" value="{$order_by_field}" />
	<input type="hidden" name="order_by_order" value="{$order_by_order}" />
</form>
<table class="table table-responsive-xl table-striped table-list">
	<thead>
		<tr>
			<th scope="col" class="pointer" data-head-field="usuario_cantar" onclick="OrderByFiltro('usuario_cantar')">Usuário <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" data-head-field="musicas_id" onclick="OrderByFiltro('musicas_id')">Nome <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" data-head-field="status" onclick="OrderByFiltro('status')">Status <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer d-none d-lg-table-cell" data-head-field="data_criacao" onclick="OrderByFiltro('data_criacao')">Data <span class="icon-order-by"></span></th>
		</tr>
	</thead>
	<tbody>
		{if empty($records)}
		<tr>
			<td colspan="4">Nenhuma música na fila encontrada!</td>
		</tr>
		{else}
		{foreach from=$records item=campos}
		<tr class="pointer row-data-select" data-row-id="{$campos.id}">
			<td data-row-usuario_criacao_nome="{$usuario_criacao_nome}"> {$campos.usuario_criacao_nome} </td>
			<td data-row-musica_id_nome="{$campos.musica_id_nome}">{$campos.musica_id_nome}</td>
			<td data-row-status="{$campos.status}">{$campos.status}</td>
			<td class="d-none d-lg-table-cell" data-row-data_criacao="{$campos.data_criacao}">{$campos.data_criacao}</td>
		</tr>
		{/foreach}
		{/if}
	</tbody>
</table>
<script type="text/javascript" src="{$app_url}jsManager/Musicas_fila/index.js?v={$ch_ver}"></script>