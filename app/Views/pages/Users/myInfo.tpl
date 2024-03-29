{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<form id="EditarForm" method="post" action="{$app_url}usuarios/salvarmyInfo">
	<input type="hidden" name="status" value="{$record.raw.status}" />
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
			<h5>Alterar Senha</h5>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<label for="senha_atual">{translate l="LBL_CURRENT_PASSWORD"}</label>
			<p><input class="form-control" type="password" name="senha_atual" value="{$record.senha_atual}"/>{if $save_data_errors.senha_atual}<span class='validate-error required'>{$save_data_errors.senha_atual}</span>{/if}</p>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<label for="senha_nova">{translate l="LBL_NEW_PASSWORD"}</label>
			<p><input class="form-control" type="password" name="senha_nova" value="{$record.senha_nova}" />{if $save_data_errors.senha_nova}<span class='validate-error required'>{$save_data_errors.senha_nova}</span>{/if}</p>
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			<label for="confirm_senha_nova">{translate l="LBL_CONFIRM_NEW_PASSWORD"}</label>
			<p><input class="form-control" type="password" name="confirm_senha_nova" value="{$record.confirm_senha_nova}" />{if $save_data_errors.confirm_senha_nova}<span class='validate-error required'>{$save_data_errors.confirm_senha_nova}</span>{/if}</p>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<button type="button" class="btn btn-outline-success btn-rounded margin-5" onclick="ValidateForm('EditarForm')"><i class="fas fa-save"></i> {translate l="LBL_UPDATE"}</button>
			<a href="{$app_url}home" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> {translate l="LBL_CANCEL"}</a>
			<button type="button" class="btn btn-outline-primary btn-rounded margin-5" onclick="resetPreferences()"><i class="fas fa-undo"></i> {translate l="LBL_RESET_PREFERENCES"}</button>
		</div>
	</div>
</form>
<script type="text/javascript" src="{$app_url}jsManager/Usuarios/myInfo.js?v={$ch_ver}"></script>