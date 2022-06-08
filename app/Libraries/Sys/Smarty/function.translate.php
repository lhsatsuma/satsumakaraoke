<?php
function smarty_function_translate(array $params, Smarty_Internal_Template $template)
{
    if(empty($params['f'])){
        $params['f'] = 'app';
    }
    return translate($params['f'], $params['l']);
}