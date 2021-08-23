<label for="{$name}">{$label}</label>{if $required} <span class="required">*</span>{/if}
<p><input class="form-control {if $error}invalid-value{/if}" type="text" name="{$name}" value="{$value}" custom_type_validation="int" {$ext_attrs} {if $disabled}disabled="true"{/if} />{if $error}<span class='validate-error required'>{$error}</span>{/if}</p>
<script type="text/javascript">
	var str_length = '';
	var length_total = 99;
	if($('input[name="{$name}"]').attr('max')){
		length_total = $('input[name="{$name}"]').attr('max').length;
	}
	for(var i=0;i<length_total;i++){
		str_length += '9';
	}
	$('input[name="{$name}"]').mask(str_length);
</script>