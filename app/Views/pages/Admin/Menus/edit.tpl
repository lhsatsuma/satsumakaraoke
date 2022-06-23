{if $msg_type}
<div class="row msg-type-{$msg_type}">
	<div class="col-12">
		<p>{$msg}</p>
	</div>
</div>
{/if}
<form id="editForm" method="post" action="{$app_url}admin/menus/salvar">
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
			<h4>{translate l="LBL_SECTION_MENU_LANGUAGES"}</h4>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{translate l="LBL_LANGUAGE"}</th>
					<th>{translate l="LBL_TRANSLATION"}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody id="menu_languages_tbody">

			</tbody>
			<tfoot>
				<tr>
					<td colspan="3"><button class="btn btn-rounded btn-outline-success" type="button" onclick="addNewMenuLanguage()">{translate l="LBL_ADD_TRANSLATION"}</button>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="row">
		<div class="col-12">
			<hr />
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<button type="button" class="btn btn-outline-success btn-rounded margin-5" onclick="ValidateForm('editForm')"><i class="fas fa-save"></i> {translate l="LBL_SAVE"}</button>
			{if $record.id}
				<a href="{$app_url}admin/menus/detail/{$record.id}" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> {translate l="LBL_CANCEL"}</a>
				{if $perms.cod_9.d}
					<button type="button" class="btn btn-outline-danger margin-5" onclick="ConfirmdeleteRecord('editForm')"><i class="fas fa-trash"></i> {translate l="LBL_DELETE"}</button>
				{/if}
			{else}
				<a href="{$app_url}admin/menus/index" class="btn btn-outline-warning btn-rounded margin-5"><i class="fas fa-undo"></i> {translate l="LBL_CANCEL"}</a>
			{/if}
		</div>
	</div>
</form>
<script type="text/javascript" src="{$app_url}jsManager/Admin/Menus/edit.js?v={$ch_ver}"></script>
<script type="text/javascript">
{foreach from=$saved_languages item=menu_language key=menu_language_key}
	addNewMenuLanguage('{$menu_language.id}','{$menu_language.language}','{$menu_language.name}');
{/foreach}
</script>