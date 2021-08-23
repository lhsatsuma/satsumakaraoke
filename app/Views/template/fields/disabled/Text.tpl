<label for="{$name}">{$label}</label>
<p><textarea class="form-control" name="{$name}" {$ext_attrs} disabled="true" >{$value|nl2br|replace:"<br />":"&#13;&#10;"}</textarea></p>