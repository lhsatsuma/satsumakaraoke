<label for="{$name_id}">{$label}</label>
<p><input type="hidden" name="{$name}" value="{$value_id}" {$ext_attrs} disabled="true" />
{if $value}
    {if $file_mimetype|strstr:"image"}
        <a class="link link-related-record" href="{$app_url}downloadManager/download/{$value}"><img src="{$app_url}downloadManager/preview/{$value}" disabled="true" style="max-height: 250px;width:100%" /></a>
    {else}
        <a class="link link-related-record" href="{$app_url}downloadManager/download/{$value}"><input class="form-control" type="text" name="{$filename_field}" value="{$value_name}" {$ext_attrs} disabled="true" /></a>
    {/if}
{else}
    <input class="form-control" type="text" name="{$filename_field}" value="{$value_name}" {$ext_attrs} disabled="true" />
{/if}
</p>