<?PHP

namespace applications\document\output\views\upsert;

use configurations\Navigator as Configuration;

use IAM\Sso;
use IAM\Configuration as IAMConfiguration;

use Knight\armor\Output;
use Knight\armor\Composer;
use Knight\armor\Language;
use Knight\armor\Navigator;

use applications\document\output\forms\Upsert as Project;

use configurations\editor\TinyMCE as TinyConfiguration;

$navigator = Navigator::get();

$policies_application_basename = IAMConfiguration::getApplicationBasename();
$policies = Sso::getPolicies($policies_application_basename . '/' . '%', 'iam/user/view/upsert', 'iam/user/action/update/me');

$project = new Project();
$project = $project->human();

$whoami = Sso::getWhoami();

Language::dictionary(__file__);
$translate = Language::getTextsNamespaceName(__namespace__);

?>

<!-- JS & CSS Layout Files -->

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>button/<?= Composer::getLockVersion('widget/button'); ?>/base.css">
<script src="<?= Configuration::WIDGETS; ?>button/<?= Composer::getLockVersion('widget/button'); ?>/base.js"></script>

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>nav/<?= Composer::getLockVersion('widget/nav'); ?>/base.css">
<script src="<?= Configuration::WIDGETS; ?>nav/<?= Composer::getLockVersion('widget/nav'); ?>/base.js"></script>

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>header/<?= Composer::getLockVersion('widget/header'); ?>/base.css">
<script src="<?= Configuration::WIDGETS; ?>header/<?= Composer::getLockVersion('widget/header'); ?>/base.js"></script>

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>menu/<?= Composer::getLockVersion('widget/menu'); ?>/base.css">
<script src="<?= Configuration::WIDGETS; ?>menu/<?= Composer::getLockVersion('widget/menu'); ?>/base.js"></script>

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/base.css">
<script src="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/base.js"></script>

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>modal/<?= Composer::getLockVersion('widget/modal'); ?>/base.css">
<script src="<?= Configuration::WIDGETS; ?>modal/<?= Composer::getLockVersion('widget/modal'); ?>/base.js"></script>

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>tabs/<?= Composer::getLockVersion('widget/tabs'); ?>/base.css">
<script src="<?= Configuration::WIDGETS; ?>tabs/<?= Composer::getLockVersion('widget/tabs'); ?>/base.js"></script>

<script type="text/javascript">
    window.page.setNavigator(<?= Output::json($navigator) ?>);
    window.page.setTranslate(<?= Output::json($translate) ?>);
    window.page.setUserPolicies(<?= Output::json($policies); ?>);
    window.page.application = '<?= IAMConfiguration::getApplicationBasename(); ?>';
    window.page.user = <?= Output::json($whoami); ?>;
    window.page.tables = {
        project: <?= Output::json($project); ?>
    };
</script>

<!-- JS & CSS Plugins Files -->

<script src="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/row/action.js"></script>
<script src="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/matrioska.js"></script>

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/dropdown/dropdown.css">
<script src="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/dropdown/dropdown.js"></script>
<script src="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/dropdown/search/search.js"></script>


<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/tooltip/tooltip.css">
<script src="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/tooltip/tooltip.js"></script>

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/file/file.css">
<script src="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/file/file.js"></script>

<link rel="stylesheet" type="text/css" href="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/textarea/textarea.css">
<script src="<?= Configuration::WIDGETS; ?>form/<?= Composer::getLockVersion('widget/form'); ?>/plugins/textarea/textarea.js"></script>

<!-- CSS View -->

<link rel="stylesheet" type="text/css" href="/cdn/applications/document/output/views/upsert/1.0.0/css/base.css">
<link rel="stylesheet" type="text/css" href="/cdn/applications/document/output/views/upsert/1.0.0/css/dependencies/connector.css">

<!-- JS View -->
<script src="https://cdn.tiny.cloud/1/<?= TinyConfiguration::KEY; ?>/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/cdn/applications/document/output/views/upsert/1.0.0/js/dependencies/connector.js"></script>
<script src="/cdn/applications/document/output/views/upsert/1.0.0/js/base.js"></script>
