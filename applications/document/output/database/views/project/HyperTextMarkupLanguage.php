<?PHP

namespace applications\document\output\database\views\project;

use Entity\Validation;

use extensions\entity\Table;

use applications\document\output\database\Project;
use applications\document\output\database\project\HyperTextMarkupLanguage as CHyperTextMarkupLanguage;

class HyperTextMarkupLanguage extends Table
{
    const COLLECTION = 'view_project_hyper_text_markup_language';

    protected function initialize()
	{        
		$id_project = new Project();
		$id_project = $id_project->getField('id_project');
		$id_project = $this->addFieldClone($id_project);
		$id_project->setRequired();
        
        $project_hyper_text_markup_language = $this->addField(CHyperTextMarkupLanguage::COLLECTION);		
        $project_hyper_text_markup_language_pattern_period = new CHyperTextMarkupLanguage();
        $project_hyper_text_markup_language_pattern = Validation::factory('Matrioska', $project_hyper_text_markup_language_pattern_period);
        $project_hyper_text_markup_language_pattern->setMultiple();
		$project_hyper_text_markup_language->setPatterns($project_hyper_text_markup_language_pattern);
        $project_hyper_text_markup_language->setProtected(false);
    }
}
