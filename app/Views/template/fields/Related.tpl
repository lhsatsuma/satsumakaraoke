<label for="{$name_id}">{$label}</label>{if $required} <span class="required">*</span>{/if}
<p>
	<input class="form-control {if $error}invalid-value{/if}" type="text" name="{$name_id}" value="{$value}" field_type_related="{$name}" {$ext_attrs} {if $disabled}disabled="true"{/if} placeholder="Digite para pesquisar..."/>
	<input class="form-control" type="hidden" name="{$name}" value="{$value_id}" />
	{if $error}<span class='validate-error required'>{$error}</span>{/if}
</p>

<script type="text/javascript">SetRelatedField({literal}{{/literal}
	'elm': 'input[name="{$name}_nome"]',
	'elm_id': 'input[name="{$name}"]',
	'model': '{$autocomplete_model}',
	'url_ajax': '{$autocomplete_ajax}',
	'is_custom': {$autocomplete_is_custom},
	'custom_where': {$custom_where},
	'callback_select': '{$callback_select}',
{literal}}{/literal});</script>