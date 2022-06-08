{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<form id="DetailForm" method="post" action="{$app_url}admin/profiles/salvar">
	<input type="hidden" name="id" value="{$record.id}" />
	<input type="hidden" name="deleted" value="{$record.deleted}" />
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.varchar.name}
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
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.datetime.date_created}
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.related.user_created}
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.datetime.date_modified}
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
			{$layout.related.user_modified}
		</div>
	</div>
</form>
{if $perms.cod_2.w}
	<div class="row">
		<div class="col-12">
			<a href="{$app_url}admin/profiles/editar/{$record.id}" class="btn btn-outline-success btn-rounded margin-5"><i class="fas fa-edit"></i> Editar</a>
		</div>
	</div>
{/if}