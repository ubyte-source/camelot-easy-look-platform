<?PHP

namespace configurations;

use Knight\Lock;

use KSQL\dialects\MySQL as Define;

defined('ENVIRONMENT_MYSQL_HOST') or define('ENVIRONMENT_MYSQL_HOST', '127.0.0.1');
defined('ENVIRONMENT_MYSQL_PORT') or define('ENVIRONMENT_MYSQL_PORT', 3306);

final class MySQL
{
	use Lock;

	const DEFAULT = [
		// default server endpoint database name
		Define::CONFIGURATION_DATABASE => ENVIRONMENT_MYSQL_DATABASE,
		// default server endpoint database username
		Define::CONFIGURATION_DATABASE_USERNAME => ENVIRONMENT_MYSQL_DATABASE_USERNAME,
		// default server endpoint database password
		Define::CONFIGURATION_DATABASE_PASSWORD => ENVIRONMENT_MYSQL_DATABASE_PASSWORD,
		// default server endpoint
		Define::CONFIGURATION_HOST => ENVIRONMENT_MYSQL_HOST,
		// default server endpoint port
		Define::CONFIGURATION_PORT => ENVIRONMENT_MYSQL_PORT
	];
}
