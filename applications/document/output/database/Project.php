<?PHP

namespace applications\document\output\database;

use Entity\Field;
use Entity\Validation;

use extensions\entity\Table;

class Project extends Table
{
	const COLLECTION = 'project';

	protected function initialize() 
	{
		$id_project = $this->addField('id_project');
		$id_project_pattern = Validation::factory('Number', 0);
		$id_project_pattern->setMin(0);
		$id_project->setPatterns($id_project_pattern);
		$id_project->addUniqueness(Field::PRIMARY);
		$id_project->setProtected();
		
		$project_name = $this->addField('project_name');
		$project_name_pattern = Validation::factory('ShowString');
		$project_name_pattern->setMin(1);
		$project_name->setPatterns($project_name_pattern);
		$project_name->setRequired();

		$id_project_application = $this->addField('id_project_application');
		$id_project_application_pattern = Validation::factory('Number', 0);
		$id_project_application_pattern->setMin(0);
		$id_project_application->setPatterns($id_project_application_pattern);
		$id_project_application->setRequired();

		$project_header = $this->addField('project_header');
		$project_header_pattern = Validation::factory('Textarea');
		$project_header_pattern->setMin(1);
		$project_header_pattern->setMax(65535);
		$project_header->setPatterns($project_header_pattern);

		$project_footer = $this->addField('project_footer');
		$project_footer_pattern = Validation::factory('Textarea');
		$project_footer_pattern->setMin(1);
		$project_footer_pattern->setMax(65535);
		$project_footer->setPatterns($project_footer_pattern);
	}

	protected function after() : void
	{
		$project_updated = $this->addField('project_updated');
		$project_updated_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_updated->setPatterns($project_updated_validator);
		$project_updated->setProtected();

		$project_created = $this->addField('project_created');
		$project_created_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_created->setPatterns($project_created_validator);
		$project_created->setProtected();
	}
}
