<?php

namespace Config;

use CodeIgniter\Config\BaseService;

use App\Libraries\Sys\ServiceCors;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function pagerext($config = null, RendererInterface $view = null, bool $getShared = true)
	{
		if (empty($config))
		{
			$config = config('Pager');
		}

		if (! $view instanceof RendererInterface)
		{
			$view = static::renderer();
		}

		return new \App\Libraries\Sys\Pager($config, $view);
	}

    public static function cors(?Cors $config = null, bool $getShared = true)
    {
        if (empty($config))
        {
            $config = config('Cors');
        }

        if ($getShared) {
            return static::getSharedInstance('cors', $config);
        }

        return new ServiceCors($config);
    }
}
