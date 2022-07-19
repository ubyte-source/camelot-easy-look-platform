<?PHP

namespace applications\document\output\actions;

use IAM\Sso;
use IAM\Configuration as IAMConfiguration;

use Knight\armor\Output;
use Knight\armor\Request;
use Knight\armor\Language;

use KSQL\Initiator as KSQL;
use KSQL\Factory;

use applications\document\output\forms\Matrioska;

$application_basename = IAMConfiguration::getApplicationBasename();
if (Sso::youHaveNoPolicies($application_basename . '/document/output/action/insert')) Output::print(false);

$matrioska = new Matrioska();
$matrioska->setFromAssociative((array)Request::post());

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

$matrioska_query = KSQL::start($database_connection, $matrioska);
$matrioska_query_insert = $matrioska_query->insert();
$matrioska_query_insert_response = $matrioska_query_insert->run();
if (null === $matrioska_query_insert_response) Output::print(false);

$database_connection->getInstance()->commit();

Output::print(true);
