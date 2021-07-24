<?php
namespace App\Libraries\Sys;

class PagerLib extends \CodeIgniter\Pager\Pager
{
	
	protected function displayLinks(string $group, string $template): string
	{
		$pager = new PagerRendererLib($this->getDetails($group));

		if (! array_key_exists($template, $this->config->templates))
		{
			throw PagerException::forInvalidTemplate($template);
		}

		return $this->view->setVar('pager', $pager)->setVar('group', $group)->render($this->config->templates[$template]);
	}
}
?>