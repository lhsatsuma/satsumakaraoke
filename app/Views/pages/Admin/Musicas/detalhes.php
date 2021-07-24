{if $save_data_errors.generic_error}
<div class="row">
	<div class="col-12">
		<p class="required">{$save_data_errors.generic_error}</p>
	</div>
</div>
{/if}
<input type="hidden" name="id" value="{$record.id}" />
<div class="row">
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.varchar.codigo}
	</div>
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.varchar.nome}
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
		{$layout.datetime.data_criacao}
	</div>
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.related.usuario_criacao}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.datetime.data_modificacao}
	</div>
	<div class="col-12 col-sm-12 col-md-6">
		{$layout.related.usuario_modificacao}
	</div>
</div>
<div class="row">
	<div class="col-12">
		<a href="{$app_url}admin/usuarios/editar/{$record.id}" class="btn btn-success margin-5"><i class="fas fa-edit"></i> Editar</a>
	</div>
</div>