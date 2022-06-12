{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<form id="EditarForm" method="post" action="{$app_url}admin/files/salvar" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{$record.id}"/>
    <input type="hidden" name="deleted" value="{$record.deleted}"/>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            {$layout.file.arquivo}
        </div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.dropdown.tipo}
		</div>
    </div>
    <div class="row">
		<div class="col-12">
			<hr />
		</div>
	</div>
    <div class="row">
        <div class="col-12">
            <button type="button" class="btn btn-outline-success btn-rounded margin-5" onclick="ValidateForm('EditarForm')"><i class="fas fa-save"></i> {translate l="LBL_SAVE"}</button>
            {if $record.id}
                <a href="{$app_url}admin/files/detail/{$record.id}" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> {translate l="LBL_CANCEL"}</a>
				{if $perms.cod_7.d}
					<button type="button" class="btn btn-outline-danger margin-5" onclick="ConfirmdeleteRecord('EditarForm')"><i class="fas fa-trash"></i> {translate l="LBL_DELETE"}</button>
				{/if}
            {else}
                <a href="{$app_url}admin/files/index" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> {translate l="LBL_CANCEL"}</a>
            {/if}
        </div>
    </div>
</form>