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
		{$layout.dropdown.status}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.related.tipo}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.varchar.nome}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.email.email}
	</div>
</div>
<div class="row">
	<div class="col-12">
		<hr />
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.datetime.ultima_troca_senha}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.datetime.last_connected}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.varchar.last_ip}
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
{if $perms.cod_1.w}
<div class="row">
	<div class="col-12">
		<a href="{$app_url}admin/usuarios/editar/{$record.id}" class="btn btn-outline-success btn-rounded margin-5"><i class="fas fa-edit"></i> Editar</a>
	</div>
</div>
{/if}