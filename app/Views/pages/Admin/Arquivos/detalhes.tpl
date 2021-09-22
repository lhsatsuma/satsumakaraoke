{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<input type="hidden" name="id" value="{$record.id}" />
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.file.arquivo}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
		{$layout.dropdown.tipo}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
		{$layout.varchar.mimetype}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.related.registro}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.varchar.tabela}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.varchar.campo}
	</div>
</div>
<div class="row">
	<div class="col-12">
		<hr />
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.datetime.data_criacao}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.related.usuario_criacao}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.datetime.data_modificacao}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.related.usuario_modificacao}
	</div>
</div>
<div class="row">
	<div class="col-12">
		{if empty($record.registro)}
		<a href="{$app_url}admin/arquivos/editar/{$record.id}" class="btn btn-success margin-5"><i class="fas fa-edit"></i> Editar</a>
		{/if}
	</div>
</div>