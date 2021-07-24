<label for="{$name}">{$label}</label>{if $required} <span class="required">*</span>{/if}<br />
<p><input class="form-control {if $error}invalid-value{/if}" autocomplete="autocomplete_off_{$name}" type="text" name="{$name}" value="{$value}" maxlength="9" {$ext_attrs} {if $disabled}disabled="true"{/if} style="display: inline-block" custom_type_validation="cep"/>{if $error}<span class='validate-error required'>{$error}</span>{/if}</p>
<script type="text/javascript">setCepField({literal}{{/literal}
	'elm': 'input[name="{$name}"]',
	'fillFields': {$autofill_fields},
	'callback_select': '{$callback_select}',
{literal}}{/literal});</script>