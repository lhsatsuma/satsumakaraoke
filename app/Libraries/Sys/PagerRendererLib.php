<?php
namespace App\Libraries\Sys;

class PagerRendererLib extends \CodeIgniter\Pager\PagerRenderer
{
	
	public function GetFirstPageNumber()
	{
		return 1;
	}
	
	public function GetLastPageNumber()
	{
		return $this->pageCount;
	}
	
	public function links(): array
	{
		$links = [];

		$uri = clone $this->uri;
		for ($i = $this->first; $i <= $this->last; $i ++)
		{
			if($i >= $this->pageCount){
				// break;
			}
			$links[] = [
				'uri'    => (string) ($this->segment === 0 ? $uri->addQuery($this->pageSelector, $i) : $uri->setSegment($this->segment, $i)),
				'title'  => (int) $i,
				'active' => ($i === $this->current || ($this->current == 0 && $i == 1)),
			];
		}

		return $links;
	}
}
?>