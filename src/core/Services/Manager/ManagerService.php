<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 31/07/18
	 * Time: 16.30
	 */

	namespace Core\Services\Manager;

	use Core\Services\Service;

	class ManagerService extends Service
	{
		/**
		 * Function to manage resources (js snippet or variables and css code, load script and style file)
		 * Will be use it for load sync and async (REST API)
		 *
		 * @return \ResourcesManager\Services\ResourcesService
		 */
		public function resoruce() {
			return app('service.resources');
		}

		/**
		 * Funtion to manage metatag of html page, include:
		 * metatag, og, extra
		 *
		 * @return \ResourcesManager\Services\MetatagService
		 */
		public function metatag() {
			return app('service.metatag');
		}
	}