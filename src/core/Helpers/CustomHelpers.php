<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 03/08/18
	 * Time: 14.02
	 */
	if (!function_exists('config_path')) {
		/**
		 * Get the configuration path.
		 *
		 * @param  string $path
		 *
		 * @return string
		 */
		function config_path($path = '')
		{
			return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
		}
	}

	if (!function_exists('logger_path')) {
		/**
		 * Get the logger_path path.
		 *
		 * @param  string $path
		 *
		 * @return string
		 */
		function logger_path($path = '')
		{
			return app()->storagePath() . '/logs' . ($path ? '/' . $path : $path);
		}
	}

	if (!function_exists('detect_version')) {
		/**
		 * Get version
		 *
		 * @param  string $separete get array with separete value of version and framework name
		 *
		 * @return string
		 */
		function detect_version(bool $separete = false)
		{
			Artisan::call('help', array('--version'));
			$detected = Artisan::output();

			if (false === $separete)
				return $detected;

			$version = trim(str_after($detected, 'Framework'));
			$framework = trim(str_before($detected, 'Framework'));

			return array('version' => $version, 'framework' => $framework);
		}
	}