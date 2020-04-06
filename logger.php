<?php
/**
 * Plugin Name: Logger
 * Plugin URI:  https://maximeculea.fr
 * Description: A simple PHP logger for WordPress.
 * Author:      MaximeCulea
 * Author URI:  https://maximeculea.fr
 * Version:     1.0.2
 */

if ( class_exists( 'Logger' ) ) {
	return;
}

class Logger {
	/**
	 * The log file path.
	 *
	 * @var string
	 */
	private $log_path;

	/**
	 * The log file extension.
	 * Default to .log.
	 *
	 * @var string
	 */
	private $log_ext = '.log';

	/**
	 * The log file max size in octets.
	 * Default to 400Mo.
	 *
	 * @var string
	 */
	private $log_size = '419430400';

	/**
	 * If the logger is ready or not.
	 *
	 * @var bool
	 */
	private $is = false;

	/**
	 * Level of gravity for the logging
	 */
	const gravity_0 = 'Emerg';
	const gravity_1 = 'Alert';
	const gravity_2 = 'Crit';
	const gravity_3 = 'Err';
	const gravity_4 = 'Warning';
	const gravity_5 = 'Notice';
	const gravity_6 = 'Info';
	const gravity_7 = 'Debug';
	const gravity_8 = 'Success';

	/**
	 * Construct the logged file
	 *
	 * @param string $log_path The path for the logging file.
	 * @param string $log_ext  Logging file extension, by default .log.
	 * @param int    $log_size Size for logging file, before spliting it.
	 *
	 * @author Maxime Culea
	 */
	function __construct( $log_path, $log_ext = '.log', $log_size = 0 ) {
		$this->log_path = $log_path;
		$this->log_ext  = $log_ext;

		if ( $log_size > 0 ) {
			$this->log_size = $log_size;
		}

		$this->is = true;
	}

	/**
	 * Log data in multiple files when full
	 *
	 * @param string $message The message.
	 * @param string $type    The message type.
	 *
	 * @author Maxime CULEA
	 */
	public function log_this( $message, $type = self::gravity_7 ) {
		if ( ! $this->is ) {
			return;
		}

		// Make the log path
		$log_full_path = $this->log_path . $this->log_ext;
		
		// Check if file exists, otherwise create it
		if ( ! file_exists( $log_full_path ) ) {
			fopen( $log_full_path, "w" );
		}

		// Maybe move the file
		$this->maybe_move_file( $log_full_path );

		// Log the error
		error_log( sprintf( '[%s][%s] %s', date( 'd-m-Y H:i:s' ), $type, $this->convert_message( $message ) ) . "\n", 3, $log_full_path );
	}

	/**
	 * Change the message into the right type.
	 *
	 * @param mixed $message
	 *
	 * @author Maxime Culea
	 *
	 * @return string
	 */
	public function convert_message( $message ) {
		if ( is_object( $message ) || is_array( $message ) ) {
			$message = print_r( $message, true );
		}

		return $message;
	}

	/**
	 * Rename the file if exceed the log max size.
	 *
	 * @param $log_full_path
	 *
	 * @author Maxime Culea
	 */
	private function maybe_move_file( $log_full_path ) {
		if ( $this->log_size > filesize( $log_full_path ) ) {
			return;
		}

		// Rename the file
		rename( $log_full_path, sprintf( '%s-%s%s', $this->log_path, date( 'Y-m-d-H-i-s' ), $this->log_ext ) );
	}
}
