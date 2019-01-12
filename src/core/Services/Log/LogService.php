<?php
	/**
	 * Created by PhpStorm.
	 * User: fabrizio
	 * Date: 31/07/18
	 * Time: 16.30
	 */

	namespace Core\Services\Log;

	use Core\Services\Service;
	use Core\Exceptions\LogsException;
	use Carbon\Carbon;
	use Monolog\Formatter\JsonFormatter;
	use Monolog\Logger;
	use Monolog\Handler\StreamHandler;
	use Illuminate\Support\Facades\Storage;

	class LogService extends Service
	{
		/**
		 * Basic log
		 * get with function getDefault()
		 *
		 * @var array
		 */
		private $default = array();

		/**
		 * Initialize the default logs with the instance of the Monolog class with today's date when the log file is
		 * created
		 *
		 * @throws \Exception
		 */
		public function __construct()
		{
			$this->initDefault($this->default);
		}

		/**
		 * Make default logs with Logger instance
		 *
		 * @param array $default
		 *
		 * @throws \Exception
		 */
		private function initDefault(array &$default = array()): void
		{
			if (null == $default)
				return;

			foreach ($default as $name) {
				$path = $this->path($name);
				$default[$name] = $this->logProcessor($path);
			}
		}

		/**
		 * Method to create path of Log
		 *
		 * @param $name
		 * @param $dataTime
		 *
		 * @return string
		 */
		private function path(string $name, $dataTime = null): string
		{
			$path = ($dataTime ? $name . '-' . Carbon::parse($dataTime)->toDateString() : $name) . '.log';
			return $path;
		}

		/**
		 * Method used to initialize the logs, returns the Monolog object, you can define the name of the log file by
		 * adding the date to it and choose the format.
		 *
		 * @param        $path
		 * @param string $formatter type of format log file
		 *
		 * @return Logger object Monolog
		 * @throws \Exception
		 */
		private function logProcessor($path, $formatter = JsonFormatter::class)
		{
			try {
				$create = new Logger(str_singular($path));
				return $create->pushHandler($this->stream($path, $formatter));
			} catch (LogsException $e) {
				$e->exception("Error process this log: " . $path, 500);
			}
		}

		/**
		 * Private method to manage the log stream
		 *
		 * @param $path
		 * @param $formatter
		 *
		 * @return StreamHandler
		 * @throws \Exception
		 */
		private function stream($path, $formatter)
		{
			$stream = new StreamHandler(logger_path($path), Logger::DEBUG);
			$stream->setFormatter(new $formatter);
			return $stream;
		}

		/**
		 * Alias logProcessor
		 * Method to create a new Log file
		 *
		 * @param string $name
		 * @param string $dataTime
		 * @param string $formatter (Moolog/Formatter class)
		 *
		 * @return Logger
		 * @throws \Exception
		 */
		public function create(string $name, $dataTime = null, $formatter = JsonFormatter::class)
		{
			$path = $this->path($name, $dataTime);
			return $this->logProcessor($path, $formatter);
		}

		/**
		 * Get basic logs
		 * Example: $this->log->getDefault('mails')->info('Example used');
		 *
		 * @param $name
		 *
		 * @return mixed
		 */
		public function getDefault($name)
		{
			if (array_has($this->default, $name))
				return $this->default[$name];

			return null;
		}

		/**
		 * Set default logs with instanced Logger obj
		 *
		 * @param array $add
		 * @param bool  $merge
		 */
		public function setDefault(array $add, bool $merge = false)
		{
			//remove element if not a string
			foreach ($add as $key => $value)
				if (false === is_string($value))
					unset($add[$key]);

			if (true === $merge)
				$this->default = array_merge($this->default, $add);
			else
				$this->default = $add;

			$this->initDefault($this->default);
		}

		/**
		 * Get log only today
		 *
		 * @param $name
		 *
		 * @return mixed|Logger|null
		 * @throws \Exception
		 */
		public function getToday(string $name)
		{
			return $this->get($name, 'today');
		}

		/**
		 * Get an existing log
		 * Recovery log through his name.
		 * Log recovery by entering the date and the name of the desired log
		 *
		 * Example:
		 *  $this->log->get('name');                 //return log if exist storage/logs/name.log
		 *  $this->log->get('name', '2018-01-01');  //return log if exist storage/logs/name-2018-01-01.log
		 *
		 * @param string $name
		 * @param string|yy-mm-dd $data
		 *
		 * @return mixed|Logger|null
		 * @throws \Exception
		 */
		public function get(string $name = '', $dataTime = null)
		{
			$path = $this->path($name, $dataTime);
			if (Storage::disk('logs')->exists($path))
				return $this->logProcessor($path);

			return null;
		}

	}