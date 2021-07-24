<label for="{$name}">{$label}</label>{if $required} <span class="required">*</span>{/if}
<p><input class="form-control {if $error}invalid-value{/if}" autocomplete="off" type="text" name="{$name}" value="{$value}" {$ext_attrs} {if $disabled}disabled="true"{/if} />{if $error}<span class='validate-error required'>{$error}</span>{/if}</p>
<script type="text/javascript">
$('input[name="{$name}"]').datepicker({literal}{{/literal}
	{if $value == ""}
		{assign var="value" value={$smarty.now|date_format:"%d/%m/%Y"}}
	{/if}
	dateFormat : 'dd/mm/yy',
	defaultDate: '{$value}',
	maxDate: new Date(moment('{$smarty.now|date_format:"%d/%m/%Y"}', "DD/MM/YYYY")),
	locale: 'pt-br',
{literal}}{/literal});
$('input[name="{$name}"]').mask('99/99/9999');
</script>