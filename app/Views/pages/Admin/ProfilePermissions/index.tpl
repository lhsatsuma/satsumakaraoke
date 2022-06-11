{if $msg}
{literal}
<script type="text/javascript">
	Swal.fire({
		heightAuto: false,
		title: 'OK',
		icon: 'success',
		text: '{/literal}{$msg}{literal}'
	});
</script>
{/literal}
{/if}
<form id="ProfilePermissionsForm" method="post" action="{$app_url}admin/ProfilePermissions/salvar">
	<p><select id="grupos" name="grupo" class="form-control" required>{$gruposAtivos}</select></p>
	<div class="table-responsive">
		<table class="table table-striped table-list mt-5">
			<thead>
				<tr class="d-flex">
					<th scope="col" class="col-4">Permissão</th>
					<th scope="col" class="col-2 text-center">
						R:&nbsp;&nbsp;<input type="checkbox" onclick="togglePermissions('left', this, 'r')" /><br />
						W:&nbsp;<input type="checkbox" onclick="togglePermissions('left', this, 'w')" /><br />
						D:&nbsp;&nbsp;<input type="checkbox" onclick="togglePermissions('left', this, 'd')" /><br />
					</th>
					<th scope="col" class="col-4">Permissão</th>
					<th scope="col" class="col-2 text-center">
						R:&nbsp;&nbsp;<input type="checkbox" onclick="togglePermissions('direita', this, 'r')" /><br />
						W:&nbsp;<input type="checkbox" onclick="togglePermissions('direita', this, 'w')" /><br />
						D:&nbsp;&nbsp;<input type="checkbox" onclick="togglePermissions('direita', this, 'd')" /><br />
					</th>
				</tr>
			</thead>
			<tbody id="tbodyProfilePermissions">
				<tr class="d-flex">
					<td scope="col" class="col-12">{translate f="Admin.ProfilePermissions" l="LBL_SELECT_PROFILE"}</td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="d-flex">
					<td scope="col" class="col-12">
						<button type="button" class="btn btn-outline-success btn-rounded margin-5" onclick="ValidateForm('ProfilePermissionsForm')"><i class="fas fa-save"></i> {translate l="LBL_SAVE"}</button>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<script type="text/javascript" src="{$app_url}jsManager/Admin/ProfilePermissions/index.js?v={$ch_ver}"></script>