<?PHP

namespace applications\document\output\database\project;

use Entity\Field;
use Entity\Validation;

use extensions\entity\Table;

use applications\document\output\database\Project;

class Javascript extends Table
{
	const COLLECTION = 'project_javascript';

	protected function initialize() : void
	{		
		$id_project_javascript = $this->addField('id_project_javascript');
		$id_project_javascript_pattern = Validation::factory('Number', 0);
		$id_project_javascript_pattern->setMin(0);
		$id_project_javascript->setPatterns($id_project_javascript_pattern);
		$id_project_javascript->addUniqueness(Field::PRIMARY);
		$id_project_javascript->setProtected();

		$project = new Project();
		$project_field_id_project = $project->getField('id_project');
		$id_project = $this->addFieldClone($project_field_id_project);
		$id_project->setRequired();

		$project_javascript_text = $this->addField('project_javascript_text');
		$project_javascript_text_pattern = Validation::factory('Textarea');
		$project_javascript_text_pattern->setMin(1);
		$project_javascript_text->setPatterns($project_javascript_text_pattern);
	}

	protected function after() : void
	{
		$project_javascript_created = $this->addField('project_javascript_created');
		$project_javascript_created_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_javascript_created->setPatterns($project_javascript_created_validator);
		$project_javascript_created->setProtected();
	
		$project_javascript_updated = $this->addField('project_javascript_updated');
		$project_javascript_updated_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_javascript_updated->setPatterns($project_javascript_updated_validator);
		$project_javascript_updated->setProtected();
	}
}
