<?PHP

namespace applications\document\output\actions;

use IAM\Sso;
use IAM\Configuration as IAMConfiguration;

use Knight\armor\Output;
use Knight\armor\Request;
use Knight\armor\Language;
use Knight\armor\Navigator;

use KSQL\Initiator as KSQL;
use KSQL\Factory;

use applications\document\output\database\Project;
use applications\document\output\forms\Matrioska;
use applications\document\output\database\project\Dependencies;
use applications\document\output\database\project\HyperTextMarkupLanguage;
use applications\document\output\database\project\CascadeStyleSheet;
use applications\document\output\database\project\Javascript;

$application_basename = IAMConfiguration::getApplicationBasename();
if (Sso::youHaveNoPolicies($application_basename . '/document/output/action/update')) Output::print(false);

$id_project = parse_url($_SERVER[Navigator::REQUEST_URI], PHP_URL_PATH);
$id_project = basename($id_project);

$matrioska = new Matrioska();
$matrioska->setFromAssociative((array)Request::post());
$matrioska->getField('id_project')->setProtected(false)->setRequired(true)->setValue($id_project);

if (!!$errors = $matrioska->checkRequired(true)->getAllFieldsWarning()) {
    Language::dictionary(__file__);
    $notice = __namespace__ . '\\' . 'notice';
    $notice = Language::translate($notice);
    Output::concatenate('notice', $notice);
    Output::concatenate('errors', $errors);
    Output::print(false);
}

$database_connection = Factory::connect();
$database_connection->getInstance()->beginTransaction();

$delete = [
    new Dependencies(),
    new HyperTextMarkupLanguage(),
    new CascadeStyleSheet(),
    new Javascript()
];

foreach ($delete as $instance) {
    $instance->getField('id_project')->setProtected(false)->setValue($id_project);
    $instance_query = KSQL::start($database_connection, $instance);
    $instance_query_delete = $instance_query->delete();
    $instance_query_delete_response = $instance_query_delete->run();
    if (null === $instance_query_delete_response) Output::print(false);
}

$project = new Project();
$project->setFromAssociative((array)Request::post());
$project->getField('id_project')->setProtected(false)->setRequired(true)->setValue($id_project);
$project->getField('project_header')->setRequired(true);
$project->getField('project_footer')->setRequired(true);
$project_query = KSQL::start($database_connection, $project);
$project_query_update = $project_query->update();
$project_query_update_response = $project_query_update->run();
if (null === $project_query_update_response) Output::print(false);

$matrioska_query = KSQL::start($database_connection, $matrioska);
$matrioska_query_insert = $matrioska_query->insert();
$matrioska_query_insert->setSkip($matrioska);
$matrioska_query_insert_response = $matrioska_query_insert->run();
if (null === $matrioska_query_insert_response) Output::print(false);

$database_connection->getInstance()->commit();

Output::print(true);
