<?php

	namespace Core\Http\Controller;

	use Laravel\Lumen\Routing\Controller as BaseController;
	use ResponseHTTP\Response\HttpResponse;

	/**
	 * Class ApiBaseController
	 * @package Core\Http\REST\v1
	 */
	class LumenBaseController extends BaseController
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
			$this->auth = app('service.auth');
			$this->acl = app('service.acl');
			$this->cache = app('service.cache');
			$this->log = app('service.log');
			$this->manager = app('service.manager');
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
		public function response($content = null, int $status = 200, array $headers = array(), bool $json = false) {
			return new HttpResponse($content, $status,$headers, $json);
		}
	}
