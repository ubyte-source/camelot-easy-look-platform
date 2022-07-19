<?PHP

namespace applications\sso\user\database\childs;

use PDO;

use IAM\Sso;
use IAM\Configuration as IAMConfiguration;

use Entity\Map as Entity;
use Entity\Validation;

use KSQL\Initiator as KSQL;
use KSQL\Factory;

use extensions\entity\Table;
use extensions\widgets\infinite\Setting as Infinite;

use applications\sso\user\database\User;

class Setting extends Table
{
	const COLLECTION = 'user_setting';
	const IDENTIFIER = [
		'application',
		'module',
		'view',
		'widget'
	];

	protected function initialize() : void
	{	
        $id_user_setting = $this->addField('id_user_setting');
		$id_user_setting_validation = Validation::factory('Number',  0);
		$id_user_setting_validation->setMin(0);
        $id_user_setting->setPatterns($id_user_setting_validation);
        $id_user_setting->setProtected(true);

		$id_user = new User();
        $id_user = $id_user->getField('id_user');
		$id_user = $this->addFieldClone($id_user);
		$id_user->addUniqueness('unique');
        $id_user->setProtected(true)->setRequired(true);

		foreach (static::IDENTIFIER as $name) {
			$field = $this->addField($name);
			$field_validator = Validation::factory('ShowString');
			$field->setPatterns($field_validator);
			$field->addUniqueness('unique');
			$field->setRequired(true);
		}

		$value = $this->addField('value');
		$value_pattern_infinite = new Infinite();
        $value_pattern_infinite = Validation::factory('Matrioska', $value_pattern_infinite);
        $value_pattern_infinite->setMultiple();
		$value->setPatterns($value_pattern_infinite);
		$value->setRequired(true);
	}

	protected function after() : void
	{
		$user_setting_created = $this->addField('user_setting_created');
		$user_setting_created_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$user_setting_created->setPatterns($user_setting_created_validator);
		$user_setting_created->setProtected();

		$user_setting_updated = $this->addField('user_setting_updated');
		$user_setting_updated_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$user_setting_updated->setPatterns($user_setting_updated_validator);
		$user_setting_updated->setProtected();
	}

	public static function getSettings(string ...$filters) : array
	{
		$instance = new static();

		$instance_field_value = $instance->getField('value');
		$instance_field_value->setProtected(false);

		$instance_fields = $instance->getFields();
		foreach ($instance_fields as $field) {
			if (0 === strpos($field->getName(), 'id')) continue;
			$field->setProtected(false)->setValue(array_shift($filters));
		}

		$database_connection = Factory::connect();

		$instance->getField('id_user')->setProtected(false)->setRequired(true)->setValue(Sso::getWhoamiKey());
		$instance_query = KSQL::start($database_connection, $instance);

		$instance_query_select = $instance_query->select();
		$instance_query_select->getLimit()->set(1);
		$instance_query_select_response = $instance_query_select->run();
		if ($instance_query_select_response === null
			|| 0 === $instance_query_select_response->rowCount()) return array();

		$setting = new static();
		$setting->setReadMode(true);
		$setting_field_value = $setting->getField($instance_field_value->getName());
		$setting->setFromAssociative($instance_query_select_response->fetch(PDO::FETCH_ASSOC));
		$setting_value_name = $setting->getField('value')->getName();
		$setting_value = $setting->getAllFieldsValues(false, false);
		return $setting_value[$setting_value_name];
	}
}
