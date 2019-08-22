<?php
/**
 * Plugin Name: Logger
 * Plugin URI:  https://maximeculea.fr
 * Description: A simple PHP logger for WordPress.
 * Author:      MaximeCulea
 * Author URI:  https://maximeculea.fr
 * Version:     1.0
 */

/**
 * Check if class already exists
 *
 * @since 0.3
 */
if ( class_exists( 'Logger' ) ) {
	return;
}

class Logger {
	/**
	 * The log file path
	 *
	 * @var string
	 */
	private $file_path;

	/**
	 * The log file extension
	 * .log by default
	 *
	 * @var string
	 */
	private $file_extension = '.log';

	/**
	 * The log file max size
	 * Here 400Mo
	 *
	 * @var string
	 */
	private $retention_size = '419430400';

	/**
	 * If the logger is ready or not
	 *
	 * @var bool
	 */
	private $is_configured = false;

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

	/**
	 * Construct the logged file
	 *
	 * @param        $file_path
	 * @param string $file_extension
	 * @param string $retention_size
	 */
	function __construct( $file_path, $file_extension = '.log', $retention_size = '' ) {
		if ( ! isset( $file_path ) || empty( $file_path ) ) {
			return false;
		}

		// Put file path
		$this->file_path = $file_path;

		// File extension
		if ( isset( $file_extension ) ) {
			$this->file_extension = $file_extension;
		}

		// Retention size
		if ( isset( $retention_size ) && ! empty( $retention_size ) && (int) $retention_size > 0 ) {
			$this->retention_size = $retention_size;
		}

		$this->is_configured = true;
	}

	/**
	 * Log data in multiple files when full
	 *
	 * @param string $message The message
	 * @param string $type    The message type
	 *
	 * @author Maxime CULEA
	 *
	 * @return bool
	 */
	public function log_this( $message, $type = self::gravity_7 ) {
		if ( false === $this->is_configured ) {
			return false;
		}

		// Make the file path
		$file_path = $this->file_path . $this->file_extension;

		// Maybe move the file
		$this->maybe_move_file( $file_path );

		// Log the error
		error_log( sprintf( '[%s][%s] %s', date( 'd-m-Y H:i:s' ), $type, self::convert_message( $message ) ) . "\n", 3, $file_path );

		return true;
	}

	/**
	 * Change the message to the right type if needed
	 *
	 * @param mixed $message
	 *
	 * @author Maxime Culea
	 *
	 * @return string
	 */
	private static function convert_message( $message ) {
		if ( is_object( $message ) || is_array( $message ) ) {
			$message = print_r( $message, true );
		}

		return $message;
	}

	/**
	 * Rename the file if exceed the file max retention
	 *
	 * @param $file_path
	 *
	 * @author Maxime Culea
	 */
	private function maybe_move_file( $file_path ) {
		// If the file exists
		if ( ! is_file( $file_path ) ) {
			return;
		}

		if ( ! $this->exceed_retention( filesize( $file_path ) ) ) {
			return;
		}

		// Rename the file
		rename( $file_path, sprintf( '%s-%s%s', $this->file_path, date( 'Y-m-d-H-i-s' ), $this->file_extension ) );
	}

	/**
	 * Check retention size is exceeded or not
	 *
	 * @param $size
	 *
	 * @author Maxime Culea
	 *
	 * @return bool
	 */
	private function exceed_retention( $size ) {
		return $size > $this->retention_size;
	}
}
