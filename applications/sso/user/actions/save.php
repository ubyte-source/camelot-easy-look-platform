<?PHP

namespace applications\sso\user\actions;

use IAM\Sso;
use IAM\Configuration as IAMConfiguration;

use Knight\armor\Output;
use Knight\armor\Language;
use Knight\armor\Request;

use Entity\Map as Entity;
use Entity\Field;

use KSQL\Initiator as KSQL;
use KSQL\Factory;

use applications\sso\user\database\User;
use applications\sso\user\database\childs\Setting;

$application_basename = IAMConfiguration::getApplicationBasename();
if (Sso::youHaveNoPolicies($application_basename . '/sso/user/action/save/widget/setting')) Output::print(false);

$setting = new Setting();
$setting->setFromAssociative((array)Request::post());
$setting->getField('id_user')->setProtected(false)->setRequired(true)->setValue(Sso::getWhoamiKey());

$widget = $setting->getField('widget')->getValue();
$widget = strtolower($widget);
$widget = ucfirst($widget);

$application = $setting->getField('application')->getValue();

$module = $setting->getField('module')->getValue();
$called = 'applications' . '\\' . $application . '\\' . $module;
$called_abstraction = $called . '\\' . 'forms' . '\\' . $widget;

$target = Entity::factory($called_abstraction);

$setting_value = $setting->getField('value');
$setting_value_valid = $target->human();
$setting_value_valid = array_column($setting_value_valid->fields, 'name');
$setting_value_valid = array_filter($setting_value->getValue(), function (Entity $entity) use ($setting_value_valid) {
	return in_array($entity->getField('name')->getValue(), $setting_value_valid);
});
$setting_value_valid = array_values($setting_value_valid);
$setting_value->setValue($setting_value_valid, Field::OVERRIDE);

if (!!$errors = $setting->checkRequired(true)->getAllFieldsWarning()) {
    Language::dictionary(__file__);
    $notice = __namespace__ . '\\' . 'notice';
    $notice = Language::translate($notice);
    Output::concatenate('notice', $notice);
    Output::concatenate('errors', $errors);
    Output::print(false);
}

$database_connection = Factory::connect();
$database_connection->getInstance()->beginTransaction();

$user = new User();
$user->getField('id_user')->setProtected(false)->setRequired(true)->setValue(Sso::getWhoamiKey());
$setting_query = KSQL::start($database_connection, $user);

$user->join($setting);

$setting_query_insert = $setting_query->insert();
$setting_query_insert->setUpdate(true);
$setting_query_insert_response = $setting_query_insert->run();
if (null === $setting_query_insert_response) Output::print(false);

$database_connection->getInstance()->commit();

Output::print(true);
