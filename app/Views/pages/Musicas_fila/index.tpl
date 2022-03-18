<form method="post" id="filtroForm" action="{$app_url}musicas_fila/index">
	<input type="hidden" id="search_user_created" name="search_user_created" value="{$search_user_created}"/>
	<input type="hidden" id="search_status" name="search_status" value="{$search_status}"/>
	<div class="row">
		<div class="col-12">
			<p><button class="btn btn-outline-{$color_user_created} btn-rounded search-button" id="SearchByAssigned">Filtrar por Meu Nome {$icon_user_created}</button>
				<button class="btn btn-outline-{$color_status_pendente} btn-rounded search-button" id="SearchByPendente">Filtrar por Pendentes {$icon_status_pendente}</button>
			</p>
		</div>
	</div>
	<input type="hidden" name="order_by_field" value="{$order_by_field}" />
	<input type="hidden" name="order_by_order" value="{$order_by_order}" />
</form>
<div class="table-responsive">
	<table class="table table-striped table-list">
		<thead>
			<tr>
				<th scope="col" class="ptr" dt-h-field="user_created" onclick="OrderByFiltro('user_created')">Usuário</th>
				<th scope="col" class="ptr" dt-h-field="musicas_id" onclick="OrderByFiltro('musicas_id')">Nome</th>
				<th scope="col" class="ptr" dt-h-field="status" onclick="OrderByFiltro('status')">Status</th>
				<th scope="col" class="ptr d-none d-lg-table-cell" dt-h-field="date_created" onclick="OrderByFiltro('date_created')">Data</th>
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
				<td dt-r-user_created_name="{$user_created_name}"> {$campos.user_created_name} </td>
				<td dt-r-musica_id_name="{$campos.musica_id_name}">{$campos.musica_id_name}</td>
				<td dt-r-status="{$campos.status}">{$campos.status}</td>
				<td class="d-none d-lg-table-cell" dt-r-date_created="{$campos.date_created}">{$campos.date_created}</td>
			</tr>
			{/foreach}
			{/if}
		</tbody>
	</table>
</div>
<table class="table table-striped table-list table-pagination">
	<tbody>
		<tr>
			<td>
				<p>Ir para página: <input size="5" type="text" class="form-control QuickGoToPage" inputmode="numeric" pattern="[0-9]*" /><button type="button" class="btn btn-outline-info btn-rounded" onclick="QuickGoToPage(this)">Ir</button></p>
			</td>
		</tr>
		<tr>
			<td>{$pagination}</td>
		</tr>
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
						<button type="button" id="InsertFilaBtn" class="btn btn-outline-success btn-rounded btn-bordered col-8">Colocar na Fila novamente</button>
					</div>
				</div>
				<div class="row margin-5">
					<div class="col-12 primary-row center">
						<button type="button" class="btn btn-outline-danger btn-bordered col-8" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{$app_url}jsManager/Musicas_fila/index.js?v={$ch_ver}"></script>
{/if}