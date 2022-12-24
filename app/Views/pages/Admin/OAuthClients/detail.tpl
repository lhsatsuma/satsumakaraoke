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
		{$layout.related.user_id}
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.varchar.client_id}
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		{$layout.varchar.client_secret}
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
{if $perms.cod_10.w}
<div class="row">
	<div class="col-12">
		<a href="{$app_url}admin/OAuthClients/edit/{$record.id}" class="btn btn-outline-success btn-rounded margin-5"><i class="fas fa-edit"></i> {translate l="LBL_EDIT"}</a>
	</div>
</div>
{/if}
<!--
Subpainel Tokens
-->
<div class="row"><div class="col-12"><hr /></div></div><div class="row"><div class="col-12" id="subPanel_oauth_tokens"></div></div>
{literal}
<script type="text/javascript">
mountSubpanel({
	'divId': 'subPanel_oauth_tokens',
	'title': translate.get('LBL_SUBPANEL_MENU_OAUTH_TOKENS'),
	'openDefault': true,
	'location_to': '/',
	'model': 'OAuthTokens/OAuthTokens',
	'lang_file': 'OAuthTokens',
	'per_page': 5,
	'fields_return': {
		'access_token': '',
		'expires': '',
		'date_modified': '',
	},
	'initial_filter': {
		'client_id': '{/literal}{$record.client_id}{literal}',
	},
	'initial_order_by': {
		'field': 'date_modified',
		'order': 'DESC',
	},
});
</script>
{/literal}