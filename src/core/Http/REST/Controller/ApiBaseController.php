<?php

	namespace Core\Http\REST\Controller;

	use Laravel\Lumen\Routing\Controller as BaseController;
	use ResponseHTTP\Response\HttpResponse;

	/**
	 * Class ApiBaseController
	 * @package Core\Http\REST\v1
	 */
	class ApiBaseController extends BaseController
	{
		/**
		 * @var ApiService
		 */
		public $api;

		/**
		 * @var AuthService
		 */
		public $auth;

		/**
		 * @var ACLService
		 */
		public $acl;

		/**
		 * @var Cache
		 */
		public $cache;

		/**
		 * @var Log
		 */
		public $log;

		public function __construct()
		{
			$this->api = app('service.api');
			$this->auth = app('service.auth');
			$this->acl = app('service.acl');
			$this->cache = app('service.cache');
			$this->log = app('service.log');
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
