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
		{$layout.varchar.name}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.bool.ativo}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.dropdown.tipo}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.varchar.url_base}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.related.menu_pai}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.int.ordem}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.varchar.icon}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.related.perm}
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
{if $perms.cod_9.w}
	<div class="row">
		<div class="col-12">
			<a href="{$app_url}admin/menus/edit/{$record.id}" class="btn btn-outline-success btn-rounded margin-5"><i class="fas fa-edit"></i> {translate l="LBL_EDIT"}</a>
		</div>
	</div>
{/if}

<!--
Subpanel Menu Languages
-->
<div class="row"><div class="col-12"><hr /></div></div><div class="row"><div class="col-12" id="subPanel_menu_languages"></div></div>
{literal}
<script type="text/javascript">
mountSubpanel({
	'divId': 'subPanel_menu_languages',
	'title': translate.get('LBL_SUBPANEL_MENU_LANGUAGES'),
	'openDefault': true,
	'location_to': 'admin/menu_languages/detail/',
	'model': 'MenuLanguages/MenuLanguages',
	'lang_file': 'MenuLanguages',
	'per_page': 5,
	'fields_return': {
		'language': '',	
		'name': '',	
		'date_modified': '',	
	},
	'initial_filter': {
		'menu_id': '{/literal}{$record.id}{literal}',
	},
	'initial_order_by': {
		'field': 'id',
		'order': 'ASC',
	},
});
</script>
{/literal}