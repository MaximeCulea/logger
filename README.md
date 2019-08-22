# Logger

A simple PHP logger for WordPress.

# What ?

It will create a file for the instance, in which you can log whatever you want by changing the message types.

# How ?

    <?php $logger = new Logger( WP_CONTENT_DIR . '/my-logger' );
    $logger->log_this( 'Log this message.', Logger::gravity_0 );

For this instance, will create a file : 'wp-content/my-logger.log'.
Then will log the message : `[d-m-Y H:i:s][Emerg] Log this message.`

## Message types

You can log messages depending on their importance or however you want to pack them.
By default it will be the gravity_7 message type that is used.

List of other message types :

	const gravity_0 = 'Emerg';
	const gravity_1 = 'Alert';
	const gravity_2 = 'Crit';
	const gravity_3 = 'Err';
	const gravity_4 = 'Warning';
	const gravity_5 = 'Notice';
	const gravity_6 = 'Info';
	const gravity_7 = 'Debug';

## Installation

This plugin is a Composer library so it can be installed in a few ways:

### Composer Autoloaded

`composer require maximeculea/logger`

`logger.php` file will be automatically autoloaded by Composer and it *won't* appear in your plugins.

### Manually as a must-use plugin

If you don't use Composer, you can manually copy `logger.php` into your `mu-plugins` folder.

I **do not** recommend using this as a normal (non-mu) plugin. It makes it too easy to disable or remove the plugin.

# Who ?

Created by Maxime Culea.

This plugin is only maintained, which means we do not guarantee some free support. Consider reporting an [issue](#issues--features-request--proposal) and be patient. 

If you really like what I do or to support me, feel free to [donate](https://www.paypal.me/MaximeCulea).

## License

Logger is licensed under the [GPLv3 or later](LICENSE.md).
