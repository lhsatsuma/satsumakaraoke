<?php
function smarty_function_translate(array $params, Smarty_Internal_Template $template)
{
    $params['f'] = $params['f'] ?: '';
    return translate($params['l'], $params['f']);
}