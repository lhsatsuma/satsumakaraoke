<label for="{$name_id}">{$label}</label>
<p><input type="hidden" name="{$name}" value="{$value_id}" {$ext_attrs} disabled="true" />
{if $value}<a class="link link-related-record" href="{$link_detail}">{/if}<input class="form-control" type="text" name="{$name_id}" value="{$value}" {$ext_attrs} disabled="true" />{if $value}</a>{/if}
</p>