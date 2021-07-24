<label for="{$name}">{$label}</label>{if $required} <span class="required">*</span>{/if}
<p><textarea class="form-control {if $error}invalid-value{/if}" type="text" name="{$name}" {$ext_attrs} {if $disabled}disabled="true"{/if}>{$value}</textarea>{if $error}<span class='validate-error required'>{$error}</span>{/if}</p>

{literal}
<script type="text/javascript">
tinymce.init({/literal}{$parameter}{literal});
</script>
{/literal}