<form method="post" id="filtroForm" action="{$app_url}musicas_fila/index">
	<input type="hidden" id="search_usuario_criacao" name="search_usuario_criacao" value="{$search_usuario_criacao}"/>
	<input type="hidden" id="search_status" name="search_status" value="{$search_status}"/>
	<div class="row">
		<div class="col-12">
			<p><button class="btn btn-{$color_usuario_criacao} search-button" id="SearchByAssigned">Filtrar por Meu Nome {$icon_usuario_criacao}</button>
				<button class="btn btn-{$color_status_pendente} search-button" id="SearchByPendente">Filtrar pelos Pendentes {$icon_status_pendente}</button>
				<button class="btn btn-{$color_status_encerrado} search-button" id="SearchByEncerrados">Filtrar pelos Encerrados {$icon_status_encerrado}</button>
			</p>
		</div>
	</div>
	<input type="hidden" name="order_by_field" value="{$order_by_field}" />
	<input type="hidden" name="order_by_order" value="{$order_by_order}" />
</form>
<table class="table table-responsive-xl table-striped table-list">
	<thead>
		<tr>
			<th scope="col">Ordem</th>
			<th scope="col" class="pointer" dt-h-field="nome" onclick="OrderByFiltro('nome')">Nome <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" dt-h-field="usuario_criacao" onclick="OrderByFiltro('usuario_criacao')">Usuário <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" dt-h-field="status" onclick="OrderByFiltro('status')">Status <span class="icon-order-by"></span></th>
			<th scope="col" class="pointer" dt-h-field="data_criacao" onclick="OrderByFiltro('data_criacao')">Data Criação <span class="icon-order-by"></span></th>
		</tr>
	</thead>
	<tbody>
		{if empty($records)}
		<tr>
			<td colspan="5">Nenhuma música na fila encontrada!</td>
		</tr>
		{else}
		{foreach from=$records item=campos}
		<tr class="pointer r-dt-slct" dt-r-id="{$campos.id}">
			<td scope="row" dt-r-ordem="{$campos.ordem}">{$campos.ordem}</td>
			<td dt-r-nome="{$campos.nome}">{$campos.nome}</td>
			<td dt-r-usuario_criacao_nome="{$usuario_criacao_nome}"> {$campos.usuario_criacao_nome} </td>
			<td dt-r-status="{$campos.status}">{$campos.status}</td>
			<td dt-r-data_criacao="{$campos.data_criacao}">{$campos.data_criacao}</td>
		</tr>
		{/foreach}
		{/if}
	</tbody>
</table>
<script type="text/javascript" src="{$app_url}jsManager/Musicas_fila/index.js?v={$ch_ver}"></script>