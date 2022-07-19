<?PHP

namespace applications\document\output\database\project;

use Entity\Field;
use Entity\Validation;

use extensions\entity\Table;

use applications\document\output\database\Project;

class HyperTextMarkupLanguage extends Table
{
	const COLLECTION = 'project_hyper_text_markup_language';

	protected function initialize() : void
	{		
		$id_project_hyper_text_markup_language = $this->addField('id_project_hyper_text_markup_language');
		$id_project_hyper_text_markup_language_pattern = Validation::factory('Number', 0);
		$id_project_hyper_text_markup_language_pattern->setMin(0);
		$id_project_hyper_text_markup_language->setPatterns($id_project_hyper_text_markup_language_pattern);
		$id_project_hyper_text_markup_language->addUniqueness(Field::PRIMARY);
		$id_project_hyper_text_markup_language->setProtected();

		$project = new Project();
		$project_field_id_project = $project->getField('id_project');
		$id_project = $this->addFieldClone($project_field_id_project);
		$id_project->setRequired();

		$project_hyper_text_markup_language_text = $this->addField('project_hyper_text_markup_language_text');
		$project_hyper_text_markup_language_text_pattern = Validation::factory('Textarea');
		$project_hyper_text_markup_language_text_pattern->setMin(1);
		$project_hyper_text_markup_language_text->setPatterns($project_hyper_text_markup_language_text_pattern);
		$project_hyper_text_markup_language_text->setRequired();
	}

	protected function after() : void
	{
		$project_hyper_text_markup_language_created = $this->addField('project_hyper_text_markup_language_created');
		$project_hyper_text_markup_language_created_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_hyper_text_markup_language_created->setPatterns($project_hyper_text_markup_language_created_validator);
		$project_hyper_text_markup_language_created->setProtected();
	
		$project_hyper_text_markup_language_updated = $this->addField('project_hyper_text_markup_language_updated');
		$project_hyper_text_markup_language_updated_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_hyper_text_markup_language_updated->setPatterns($project_hyper_text_markup_language_updated_validator);
		$project_hyper_text_markup_language_updated->setProtected();
	}
}
