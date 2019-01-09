<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 09/01/19
	 * Time: 16.41
	 */

	namespace Core\Http\Controller\Traits;

	use ResponseHTTP\Response\HttpResponse;

	trait BaseServices
	{
		/**
		 * @var \Core\Services\Api\ApiService
		 */
		public $api;

		/**
		 * @var \Core\Services\Auth\AuthService
		 */
		public $auth;

		/**
		 * @var \Core\Services\ACL\ACLService
		 */
		public $acl;

		/**
		 * @var \CacheSystem\Services\CacheService
		 */
		public $cache;

		/**
		 * @var \Core\Services\Log\LogService
		 */
		public $log;

		/**
		 * @var \Core\Services\Manager\ManagerService
		 */
		public $manager;

		public function __construct()
		{
			$this->api = app('service.api');
			$this->acl = app('service.acl');
			$this->cache = app('service.cache');
			$this->log = app('service.log');
			$this->manager = app('service.manager');

			if(app()->resolved('service.auth'))
				$this->auth = app('service.auth');
		}

		/**
		 *   Helper function to create Response http
		 *
		 * @param null  $content
		 * @param int   $status
		 * @param array $headers
		 * @param bool  $json
		 *
		 * @return \ResponseHTTP\Response\HttpResponse
		 */
		public function response($content = null, int $status = 200, array $headers = array(), bool $json = false)
		{
			return new HttpResponse($content, $status, $headers, $json);
		}
	}