<?php

	namespace Core\Http\Controller;

	use Illuminate\Routing\Controller as BaseController;
	use Core\Http\Controller\Traits\BaseServices;

	/**
	 * Class LaravelBaseController
	 * @package Core\Http\REST\v1
	 */
	class LaravelBaseController extends BaseController
	{
		use BaseServices;
	}
