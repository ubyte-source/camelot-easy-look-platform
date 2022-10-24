<?PHP

namespace applications\document\output\actions;

use PDO;

use IAM\Sso;
use IAM\Request as IAMRequest;
use IAM\Configuration as IAMConfiguration;

use Knight\armor\Output;
use Knight\armor\output\Data;
use Knight\armor\Request;

use KSQL\Initiator as KSQL;
use KSQL\Factory;

use extensions\widgets\infinite\Setting;

use applications\document\output\forms\Read as Project;

$application_basename = IAMConfiguration::getApplicationBasename();
if (Sso::youHaveNoPolicies($application_basename . '/document/output/action/read')) Output::print(false);

$project = new Project();
$project->setSafeMode(false);
$project_fields = $project->getFields();
foreach ($project_fields as $field) $field->setRequired(true);

$project_field_values = Request::post();
$project_field_values = array_filter((array)$project_field_values, function ($value) {
    return is_string($value) && strlen($value);
});
$project->setFromAssociative($project_field_values);

$project->getField('id_project')->setProtected(false);
$project_query = KSQL::start(Factory::connect(), $project);
$project_query_select = $project_query->select();

$or = Request::get('force-use-or');
$or = filter_var($or, FILTER_VALIDATE_BOOLEAN);
if (true === $or && false === Sso::youHaveNoPolicies($application_basename . '/document/output/action/read/or')) $project_query_select->pushTablesUsingOr($project);

$project_query_select_limit = $project_query_select->getLimit();
if (!!$count_offset = Request::get('offset')) $project_query_select_limit->setOffset($count_offset);
if (!!$count = Request::get('count')) $project_query_select_limit->set($count);

$project_query_select_response = $project_query_select->run();
if (null === $project_query_select_response) Output::print(false);

$project = new Project();
$project->setSafeMode(false)->setReadMode(true);

$project_query_select_response_count = $project_query_select_response->rowCount();
$project_query_select_response = $project_query_select_response->fetchAll(PDO::FETCH_ASSOC);
$project_query_select_response = array_map(function (array $row) use ($project) {
    $clone = clone $project;
    $clone->setFromAssociative($row, $row);
    return $clone->getAllFieldsValues(false, false);
}, $project_query_select_response);

IAMRequest::setOverload(
    'sso/application/action/read',
    'sso/application/action/read/all'
);

$remotes_only = Data::only($project->getField('id_project')->getName());
$remotes = $project->getRemotes();
foreach ($remotes as $remote) 
    $remote->getData()->get($project_query_select_response, 0, (array)Request::post());


if (false === empty($remotes_only)) {
    $remotes_only_filled = array_fill_keys($remotes_only, null);
    array_walk($project_query_select_response, function (array &$item) use ($remotes_only_filled) {
        $item = array_intersect_key($item, $remotes_only_filled);
    });
}
    
Output::concatenate(Setting::COMPLETE, $project_query_select_response_count === count($project_query_select_response));
Output::concatenate(Output::APIDATA, $project_query_select_response);
Output::print(true);
