<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 27/12/18
	 * Time: 13.32
	 */

	namespace Core\Services;

	use ResponseHTTP\Response\HttpResponse;

	abstract class Service
	{
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
		protected function fail(int $statusCode = null, array $data = array(), string $message = null): object {
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
		protected function success(int $statusCode = null, array $data = array(), string $message = null): object {
			return StatusService::set(TRUE, $statusCode, $data, $message);
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
		protected function response($content = null, int $status = 200, array $headers = array(), bool $json = false) {
			return new HttpResponse($content, $status,$headers, $json);
		}
	}