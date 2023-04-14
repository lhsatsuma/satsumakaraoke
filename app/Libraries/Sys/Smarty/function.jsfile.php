<?php
function smarty_function_jsfile(array $params, Smarty_Internal_Template $template)
{
    $cache_ver = GetCacheVersion();
    $app_url = base_url();
    return "<script type='text/javascript' src='{$app_url}jsManager/{$params['f']}?v={$cache_ver}'></script>";
}