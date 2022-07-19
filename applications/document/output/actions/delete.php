<?PHP

namespace applications\document\output\actions;

use IAM\Sso;
use IAM\Configuration as IAMConfiguration;

use Knight\armor\Output;
use Knight\armor\Language;
use Knight\armor\Navigator;

use KSQL\Initiator as KSQL;
use KSQL\Factory;

use applications\document\output\database\Project;

$application_basename = IAMConfiguration::getApplicationBasename();
if (Sso::youHaveNoPolicies($application_basename . '/document/output/action/delete')) Output::print(false);

$id_project = parse_url($_SERVER[Navigator::REQUEST_URI], PHP_URL_PATH);
$id_project = basename($id_project);

$project = new Project();
$project_fields = $project->getFields();
foreach ($project_fields as $field) $field->setRequired(false);

$project->getField('id_project')->setRequired(true)->setProtected(false)->setValue($id_project);

if (!!$errors = $project->checkRequired()->getAllFieldsWarning()) {
    Language::dictionary(__file__);
    $notice = __namespace__ . '\\' . 'notice';
    $notice = Language::translate($notice);
    Output::concatenate('notice', $notice);
    Output::concatenate('errors', $errors);
    Output::print(false);
}

$database_connection = Factory::connect();
$database_connection->getInstance()->beginTransaction();

$project_query = KSQL::start($database_connection, $project);
$project_query_delete = $project_query->delete();
$project_query_delete_response = $project_query_delete->run();
if (null === $project_query_delete_response) Output::print(false);

$database_connection->getInstance()->commit();

Output::print(true);
