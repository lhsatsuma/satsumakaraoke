{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<input type="hidden" name="id" value="{$record.id}" />
<div class="row">
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.varchar.codigo}
	</div>
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.varchar.name}
	</div>
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.link.link}
	</div>
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.varchar.md5}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.dropdown.origem}
	</div>
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.dropdown.tipo}
	</div>
</div>
<div class="row">
	<div class="col-12">
		<hr />
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.datetime.date_created}
	</div>
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.related.user_created}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.datetime.date_modified}
	</div>
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.related.user_modified}
	</div>
</div>
<div class="row">
	<div class="col-12">
		<a href="{$app_url}admin/usuarios/editar/{$record.id}" class="btn btn-outline-success btn-rounded margin-5"><i class="fas fa-edit"></i> Editar</a>
	</div>
</div>