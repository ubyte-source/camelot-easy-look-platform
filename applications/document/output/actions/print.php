<?PHP

namespace applications\document\output\actions;

use PDO;

use configurations\Navigator as Configuration;

use IAM\Sso;
use IAM\Request;
use IAM\Configuration as IAMConfiguration;

use Knight\armor\Output;
use Knight\armor\Composer;
use Knight\armor\Language;
use Knight\armor\Navigator;

use KSQL\Initiator as KSQL;
use KSQL\Factory;

use applications\document\output\database\views\Project;

const REPLACER = [
    '/^(.*)js$/' => '<script src="$0"></script>' . "\n",
    '/^(.*)ss$/' => '<link rel="stylesheet" type="text/css" href="$0" />' . "\n"
];

$parameters = parse_url($_SERVER[Navigator::REQUEST_URI], PHP_URL_PATH);
$parameters = explode(chr(47), $parameters);
$parameters = array_filter($parameters, 'strlen');
$parameters = array_values($parameters);
$parameters = array_slice($uri, 1 + Navigator::getDepth());

$application_basename = IAMConfiguration::getApplicationBasename();
if (Sso::youHaveNoPolicies($application_basename . '/document/output/action/print')) Output::print(false);

$project = new Project();
$project_fields = $project->getFields();
foreach ($project_fields as $field) $field->setProtected(true)->setRequired(true);

$project_value = array_shift($parameters);
$project->getField('id_project')->setProtected(false)->setValue($project_value);

if (!!$errors = $project->checkRequired(true)->getAllFieldsWarning()) {
    Language::dictionary(__file__);
    $notice = __namespace__ . '\\' . 'notice';
    $notice = Language::translate($notice);
    Output::concatenate('notice', $notice);
    Output::concatenate('errors', $errors);
    Output::print(false);
}

$database_connection = Factory::connect();

$project_query = KSQL::start($database_connection, $project);
$project_query_select = $project_query->select();
$project_query_select->getLimit()->set(1);
$project_query_select_response = $project_query_select->run();
if (null === $project_query_select_response
    || 1 !== $project_query_select_response->rowCount()) Output::print(false);

$detail = new Project();
$detail->setSafeMode(false)->setReadMode(true);
$detail_value = $project_query_select_response->fetch(PDO::FETCH_ASSOC);
$detail->setFromAssociative($detail_value, $detail_value);
$detail_value = $detail->getAllFieldsValues(false, false);

$detail_project_dependencies = $detail->getField('project_dependencies')->getName();
$detail_project_dependencies = $detail_value[$detail_project_dependencies];
$detail_project_dependencies = array_column($detail_project_dependencies, 'project_dependencies_url');
$detail_project_dependencies = preg_filter(array_keys(REPLACER), array_values(REPLACER), $detail_project_dependencies);

$detail_project_hyper_text_markup_language = $detail->getField('project_hyper_text_markup_language')->getName();
$detail_project_hyper_text_markup_language = $detail_value[$detail_project_hyper_text_markup_language];
$detail_project_hyper_text_markup_language = array_column($detail_project_hyper_text_markup_language, 'project_hyper_text_markup_language_text');
$detail_project_hyper_text_markup_language = preg_filter('/\n/m', '', $detail_project_hyper_text_markup_language);
$detail_project_hyper_text_markup_language = preg_filter('/^.*$/', '<tr><td><article>$0</article></td></tr>', $detail_project_hyper_text_markup_language);

$detail_project_cascade_style_sheet = $detail->getField('project_cascade_style_sheet')->getName();
$detail_project_cascade_style_sheet = $detail_value[$detail_project_cascade_style_sheet];
$detail_project_cascade_style_sheet = array_column($detail_project_cascade_style_sheet, 'project_cascade_style_sheet_text');

$detail_project_javascript = $detail->getField('project_javascript')->getName();
$detail_project_javascript = $detail_value[$detail_project_javascript];
$detail_project_javascript = array_column($detail_project_javascript, 'project_javascript_text');

$detail_header = $detail->getField('project_header')->getValue();
$detail_footer = $detail->getField('project_footer')->getValue();

header("Content-type: text/html");

ob_start();

?><!DOCTYPE html>
<html translate="no" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link href="https://fonts.googleapis.com" rel="dns-prefetch"/>
        <meta name="theme-color" content="#ffffff">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="x-ua-compatible" content="IE=edge">
        <title><?= $detail->getField('project_name')->getValue(); ?></title>
        <!-- Files External-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <?= implode($detail_project_dependencies); ?>
        <!-- User Code -->
        <link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>asset/<?= Composer::getLockVersion('widget/asset'); ?>/css/reset.css">
        <style><?= implode($detail_project_cascade_style_sheet); ?></style>
        <script type="text/javascript">window.parameters = <?= Output::json($parameters); ?>;window.init = function () {};</script>
        <script type="text/javascript"><?= implode($detail_project_javascript); ?></script>
        <style>
            @page { size: A4; margin: 0 5mm; }
            html, body { height: 100%; width: 64em; }
            body > header, body > footer { position: fixed; right: 0; left: 0; margin: 0; padding: 2mm 0; }
            body > header { top: 5mm; margin: 0; height: 10mm !important; }
            body > footer { bottom: 0; margin: 0; height: 25mm !important; }
            body > table > thead > tr > th > div { height: 20mm; }
            body > table > tbody > tr { float: none !important; display: block !important; page-break-before: always !important; }
            body > table > tbody > tr > td > article {  }
            body > table > tbody > tr > td > article > p { margin: 0 !important; padding: 0 !important; }
            body > table > tbody > tr > td > article ul, body > table > tbody > tr > td > article ol { list-style-type: disc; margin: 1em 0; padding-left: 2.5em; }
            body > table > tbody > tr > td > article ul > li, body > table > tbody > tr > td > article ol > li { margin: 0.5em 0; }
            body > table > tfoot > tr > td > div { height: 30mm; }
            @media print {
                tbody { display: table-row-group; } 
                thead { display: table-header-group; } 
                tfoot { display: table-footer-group; }
            }
        </style>
    </head>
    <body onload="init()">
        <table>
            <thead><tr><th><div>&nbsp;</div></th></tr></thead>
            <tbody><?= implode($detail_project_hyper_text_markup_language); ?></tbody>
            <tfoot><tr><td><div>&nbsp;</div></td></tr></tfoot>
        </table>
        <header><?= $detail_header; ?></header>
        <footer><?= $detail_footer; ?></footer>
    </body>
</html>
<?php

$response = ob_get_contents();

ob_end_clean();

exit($response);

?>
