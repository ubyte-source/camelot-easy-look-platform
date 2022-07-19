<?PHP

namespace applications\sso\user\database;

use IAM\Sso;

use Entity\Field;
use Entity\Validation;

use extensions\entity\Table;

class User extends Table
{
	const COLLECTION = 'user';

	protected function initialize()
	{
		$id_user = $this->addField('id_user');
		$id_user_pattern = Validation::factory('Number', 0);
		$id_user_pattern->setMin(0);
		$id_user->setPatterns($id_user_pattern);
		$id_user->addUniqueness(Field::PRIMARY);
		$id_user->setProtected();
	}

	protected function after() : void
	{
		$user_created = $this->addField('user_created');
		$user_created_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$user_created->setPatterns($user_created_validator);
		$user_created->setProtected();

		$user_updated = $this->addField('user_updated');
		$user_updated_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$user_updated->setPatterns($user_updated_validator);
		$user_updated->setProtected();
	}
}
