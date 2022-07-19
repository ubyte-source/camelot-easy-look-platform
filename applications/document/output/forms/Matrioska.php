<?PHP

namespace applications\document\output\forms;

use Entity\Validation;

use applications\document\output\database\Project;

use applications\document\output\forms\project\Dependencies;
use applications\document\output\forms\project\HyperTextMarkupLanguage;
use applications\document\output\forms\project\CascadeStyleSheet;
use applications\document\output\forms\project\Javascript;

class Matrioska extends Project
{
    const APPLICATION = '#basename#';
    const APPLICATION_READ = '/api/sso/user/gateway/iam/sso/application/read';
    const APPLICATION_READ_RESPONSE = 'data';
    const APPLICATION_READ_GRAB_IDENTITY = '_key';
    const APPLICATION_READ_RESPONSE_FIELDS = [
        'basename'
    ];

    protected function initialize()
	{
        parent::initialize();

        $id_project_application = $this->getField('id_project_application');
        $id_project_application->setProtected(false)->setRequired(true);
		$id_project_application_pattern = Validation::factory('Enum');
        $id_project_application_pattern_search = $id_project_application_pattern->getSearch();
        $id_project_application_pattern_search->setURL(static::APPLICATION_READ);
		$id_project_application_pattern_search->setUnique(static::APPLICATION_READ_GRAB_IDENTITY);
        $id_project_application_pattern_search->setResponse(static::APPLICATION_READ_RESPONSE);
        $id_project_application_pattern_search->pushFields(...static::APPLICATION_READ_RESPONSE_FIELDS);
        $id_project_application_pattern_search->setLabel(static::APPLICATION);
        $id_project_application->setPatterns($id_project_application_pattern);

        $project_dependencies = $this->addField(Dependencies::COLLECTION);		
        $project_dependencies_pattern_write = new Dependencies();
        $project_dependencies_pattern = Validation::factory('Matrioska', $project_dependencies_pattern_write);
        $project_dependencies_pattern->setMultiple(true);
		$project_dependencies->setPatterns($project_dependencies_pattern);
        $project_dependencies->setRequired(false);

        $project_cascade_style_sheet = $this->addField(CascadeStyleSheet::COLLECTION);		
        $project_cascade_style_sheet_pattern_write = new CascadeStyleSheet();
        $project_cascade_style_sheet_pattern = Validation::factory('Matrioska', $project_cascade_style_sheet_pattern_write);
		$project_cascade_style_sheet->setPatterns($project_cascade_style_sheet_pattern);
        $project_cascade_style_sheet->setRequired();

        $project_javascript = $this->addField(Javascript::COLLECTION);		
        $project_javascript_pattern_write = new Javascript();
        $project_javascript_pattern = Validation::factory('Matrioska', $project_javascript_pattern_write);
		$project_javascript->setPatterns($project_javascript_pattern);
        $project_javascript->setRequired();

        $project = $this->addField(HyperTextMarkupLanguage::COLLECTION);		
        $project_pattern_write = new HyperTextMarkupLanguage();
        $project_pattern = Validation::factory('Matrioska', $project_pattern_write);
        $project_pattern->setMultiple(true);
		$project->setPatterns($project_pattern);
        $project->setRequired();
    }
}
