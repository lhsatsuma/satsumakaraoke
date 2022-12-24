{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<form id="EditarForm" method="post" action="{$app_url}admin/OAuthClients/salvar">
	<input type="hidden" name="id" value="{$record.id}" />
	<input type="hidden" name="deleted" value="{$record.deleted}" />
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.varchar.name}
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.related.user_id}
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.varchar.client_id}
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.varchar.client_secret}
			<button type="button" class="btn btn-success" id="gen_client_secret" style="font-size: 0.8rem;"><i class="fas fa-sync-alt"></i> {translate l="LBL_GEN_CLIENT_SECRET"}</button>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.dropdown.grant_types}
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.dropdown.scope}
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<button type="button" class="btn btn-outline-success btn-rounded margin-5" onclick="ValidateFormCstm('EditarForm')"><i class="fas fa-save"></i> {translate l="LBL_SAVE"}</button>
			{if $record.id}
				<a href="{$app_url}admin/OAuthClients/detail/{$record.id}" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> {translate l="LBL_CANCEL"}</a>
				{if $perms.cod_1.d}
					<button type="button" class="btn btn-outline-danger margin-5" onclick="ConfirmdeleteRecord('EditarForm')"><i class="fas fa-trash"></i> {translate l="LBL_DELETE"}</button>
				{/if}
			{else}
				<a href="{$app_url}admin/OAuthClients/index" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> {translate l="LBL_CANCEL"}</a>
			{/if}
		</div>
	</div>
</form>
{jsfile f="Admin/OAuthClients/edit.js"}