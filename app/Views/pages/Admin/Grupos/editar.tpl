{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<form id="EditarForm" method="post" action="{$app_url}admin/grupos/salvar">
	<input type="hidden" name="id" value="{$record.id}" />
	<input type="hidden" name="deletado" value="{$record.deletado}" />
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.varchar.nome}
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.bool.ativo}
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<hr />
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<button type="button" class="btn btn-outline-success btn-rounded margin-5" onclick="ValidateForm('EditarForm')"><i class="fas fa-save"></i> Salvar</button>
			<a href="{$app_url}admin/grupos/index" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> Cancelar</a>
			{if $record.id && $perms.cod_12}
				<button type="button" class="btn btn-outline-danger margin-5" onclick="ConfirmdeleteRecord('EditarForm')"><i class="fas fa-trash"></i> Deletar</button>
			{/if}
		</div>
	</div>
</form>