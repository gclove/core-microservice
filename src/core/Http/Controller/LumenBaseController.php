<?php

	namespace Core\Http\Controller;

	use Laravel\Lumen\Routing\Controller as BaseController;
	use Core\Http\Controller\Traits\BaseServices;

	/**
	 * Class LumenBaseController
	 * @package Core\Http\REST\v1
	 */
	class LumenBaseController extends BaseController
	{
		use BaseServices;
	}
