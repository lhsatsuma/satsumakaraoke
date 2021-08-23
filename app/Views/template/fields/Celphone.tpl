<label for="{$name}">{$label}</label>{if $required} <span class="required">*</span>{/if}
<p><input class="form-control {if $error}invalid-value{/if}" type="text" name="{$name}" value="{$value}" custom_type_validation="telephone" {$ext_attrs} {if $disabled}disabled="true"{/if} />{if $error}<span class='validate-error required'>{$error}</span>{/if}</p>
<script type="text/javascript">
	$('input[name="{$name}"]').mask('(99) 99999-9999');
</script>