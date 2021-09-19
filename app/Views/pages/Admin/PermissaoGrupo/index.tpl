{if $msg}
{literal}
<script type="text/javascript">
	Swal.fire({
		title: 'OK',
		icon: 'success',
		text: '{/literal}{$msg}{literal}'
	});
</script>
{/literal}
{/if}
<form id="PermissaoGrupoForm" method="post" action="{$app_url}admin/permissao_grupo/salvar">
	<p><select id="grupos" name="grupo" class="form-control" required>{$gruposAtivos}</select></p>
	<table class="table table-responsive-xl table-striped table-list mt-5">
		<thead>
			<tr class="d-flex">
				<th scope="col" class="col-5">Permissão</th>
				<th scope="col" class="col-1">Habilitado <input type="checkbox" onclick="togglePermissoes('esquerda', this)" /></th>
				<th scope="col" class="col-5">Permissão</th>
				<th scope="col" class="col-1">Habilitado <input type="checkbox" onclick="togglePermissoes('direita', this)" /></th>
			</tr>
		</thead>
		<tbody id="tbodyPermissaoGrupo">
			<tr class="d-flex">
				<td scope="col" class="col-12">Selecione o Grupo</td>
			</tr>
		</tbody>
		<tfoot>
			<tr class="d-flex">
				<td scope="col" class="col-12">
					<button type="button" class="btn btn-outline-success btn-rounded margin-5" onclick="ValidateForm('PermissaoGrupoForm')"><i class="fas fa-save"></i> Salvar</button>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript" src="{$app_url}jsManager/Admin/PermissaoGrupo/index.js?v={$ch_ver}"></script>