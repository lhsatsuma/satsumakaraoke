<?php
namespace App\Libraries\Sys;

use CodeIgniter\Pager\Exceptions\PagerException;

class Pager extends \CodeIgniter\Pager\Pager
{
	
	protected function displayLinks(string $group, string $template): string
	{
		$pager = new PagerRenderer($this->getDetails($group));

		if (! array_key_exists($template, $this->config->templates))
		{
			throw PagerException::forInvalidTemplate($template);
		}

		return $this->view->setVar('pager', $pager)->setVar('group', $group)->render($this->config->templates[$template]);
	}
}
?>