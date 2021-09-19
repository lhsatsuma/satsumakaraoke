<p><select id="grupos" class="form-control">{$gruposAtivos}</select></p>
<p><button type="button" class="btn btn-outline-success btn-rounded">Procurar</button></p>

<table class="table table-responsive-xl table-striped table-list tb-rst-fltr">
	<thead>
		<tr>
			<th scope="col">Permissão</th>
			<th scope="col">Habilitado</th>
			<th scope="col">Permissão</th>
			<th scope="col">Habilitado</th>
		</tr>
	</thead>
	<tbody id="tbodyPermissaoGrupo">
		<tr>
			<td colspan="4">Selecione o Grupo</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript" src="{$app_url}jsManager/Admin/PermissaoGrupo/index.js?v={$ch_ver}"></script>