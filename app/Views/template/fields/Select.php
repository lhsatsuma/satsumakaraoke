<label for="{$name}">{$label}</label>{if $required} <span class="required">*</span>{/if}
<p><select class="form-control {if $error}invalid-value{/if}" name="{$name}" {$ext_attrs} {if $disabled}disabled="true"{/if} >{$options}</select>{if $error}<span class='validate-error required'>{$error}</span>{/if}</p>