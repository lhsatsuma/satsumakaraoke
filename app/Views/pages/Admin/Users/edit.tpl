{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<form id="EditarForm" method="post" action="{$app_url}admin/users/salvar">
	<input type="hidden" name="id" value="{$record.id}" />
	<input type="hidden" name="deleted" value="{$record.deleted}" />
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.dropdown.status}
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.related.profile}
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.varchar.name}
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.email.email}
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.telephone.telefone}
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.celphone.celular}
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.dropdown.timezone}
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<hr />
		</div>
	</div>
	<div class="row">
		<div class="col-12 margin-b-10">
			<h5>Criar/Atualizar Senha</h5>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<label for="senha_nova">Senha</label>
			<p><input class="form-control" type="password" name="senha_nova" value="{$record.senha_nova}" />{if $save_data_errors.senha_nova}<span class='validate-error required'>{$save_data_errors.senha_nova}</span>{/if}</p>
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<label for="confirm_senha_nova">Confirmação Senha</label>
			<p><input class="form-control" type="password" name="confirm_senha_nova" value="{$record.confirm_senha_nova}" />{if $save_data_errors.confirm_senha_nova}<span class='validate-error required'>{$save_data_errors.confirm_senha_nova}</span>{/if}</p>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<button type="button" class="btn btn-outline-success btn-rounded margin-5" onclick="ValidateForm('EditarForm')"><i class="fas fa-save"></i> {translate l="LBL_SAVE"}</button>
			{if $record.id}
				<a href="{$app_url}admin/users/detail/{$record.id}" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> {translate l="LBL_CANCEL"}</a>
				{if $perms.cod_1.d}
					<button type="button" class="btn btn-outline-danger margin-5" onclick="ConfirmdeleteRecord('EditarForm')"><i class="fas fa-trash"></i> {translate l="LBL_DELETE"}</button>
				{/if}
			{else}
				<a href="{$app_url}admin/users/index" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> {translate l="LBL_CANCEL"}</a>
			{/if}
		</div>
	</div>
</form>