<?php

	namespace Core\Http\REST\Controller;

	use Laravel\Lumen\Routing\Controller as BaseController;
	use ResponseHTTP\Response\HttpResponse;
	use StatusService;

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
		 * @var Response
		 */
		public $response;

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

		/**
		 * @var Log
		 */
		public $helpers;

		public function __construct()
		{
			$this->api = app('service.api');
			$this->response = app('service.response');
			$this->auth = app('service.auth');
			$this->acl = app('service.acl');
			$this->cache = app('service.cache');
			$this->log = app('service.log');
		}

		public function response() {
			return new HttpResponse();
		}


		/**
		 *    Helper function to wrap ServiceStatus return.
		 *
		 * @param string $statusCode
		 *    The status code to be passed to ServiceStatus.
		 * @param        array
		 *    Custom data.
		 * @param string $message
		 *    A message.
		 *
		 * @return StatusService
		 */
		public function fail(int $statusCode = null, array $data = array(), string $message = null): object {
			return StatusService::set(FALSE, $statusCode, $data, $message);
		}

		/**
		 *    Helper function to wrap StatusService return.
		 *
		 * @param array         $data
		 *    The data this service is returing.
		 * @param null          $statusCode
		 * @param string|string $message
		 *    A message.
		 *
		 * @return ServiceStatus
		 */
		public function success(int $statusCode = null, array $data = array(), string $message = null): object {
			return StatusService::set(TRUE, $statusCode, $data, $message);
		}
	}
