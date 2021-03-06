<form method="post" id="filtroForm" action="{$app_url}musicas_fila/index">
	<input type="hidden" id="search_usuario_criacao" name="search_usuario_criacao" value="{$search_usuario_criacao}"/>
	<input type="hidden" id="search_status" name="search_status" value="{$search_status}"/>
	<div class="row">
		<div class="col-12">
			<p><button class="btn btn-{$color_usuario_criacao} search-button" id="SearchByAssigned">Filtrar por Meu Nome {$icon_usuario_criacao}</button>
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
			<th scope="col" class="ptr" dt-h-field="usuario_criacao" onclick="OrderByFiltro('usuario_criacao')">Usuário</th>
			<th scope="col" class="ptr" dt-h-field="musicas_id" onclick="OrderByFiltro('musicas_id')">Nome</th>
			<th scope="col" class="ptr" dt-h-field="status" onclick="OrderByFiltro('status')">Status</th>
			<th scope="col" class="ptr d-none d-lg-table-cell" dt-h-field="data_criacao" onclick="OrderByFiltro('data_criacao')">Data</th>
		</tr>
	</thead>
	<tbody>
		{if empty($records)}
		<tr>
			<td colspan="4">Nenhuma música na fila encontrada!</td>
		</tr>
		{else}
		{foreach from=$records item=campos}
		<tr class="ptr r-dt-slct" dt-r-id="{$campos.id}">
			<input type="hidden" dt-r-musica_id="{$campos.musica_id}" />
			<td dt-r-usuario_criacao_nome="{$usuario_criacao_nome}"> {$campos.usuario_criacao_nome} </td>
			<td dt-r-musica_id_nome="{$campos.musica_id_nome}">{$campos.musica_id_nome}</td>
			<td dt-r-status="{$campos.status}">{$campos.status}</td>
			<td class="d-none d-lg-table-cell" dt-r-data_criacao="{$campos.data_criacao}">{$campos.data_criacao}</td>
		</tr>
		{/foreach}
		{/if}
	</tbody>
</table>
{if !$bdOnly}
<div class="modal fade" id="SelectedRowModal" tabindex="-1" role="dialog" aria-labelledby="SelectedRowModalLabel" aria-hidden="true">
	<input type="hidden" id="IdInsertModal" />
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="SelectedRowModalLabel"></h6>
			</div>
			<div class="modal-body">
				<div class="row margin-5">
					<div class="col-12 primary-row center">
						<button type="button" id="InsertFilaBtn" class="btn btn-success btn-bordered col-8">Colocar na Fila novamente</button>
					</div>
				</div>
				<div class="row margin-5">
					<div class="col-12 primary-row center">
						<button type="button" class="btn btn-danger btn-bordered col-8" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/Musicas_fila/index.js?v={$ch_ver}"></script>
{/if}