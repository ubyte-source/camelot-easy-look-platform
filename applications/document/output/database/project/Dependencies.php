<?PHP

namespace applications\document\output\database\project;

use Entity\Field;
use Entity\Validation;

use extensions\entity\Table;

use applications\document\output\database\Project;

class Dependencies extends Table
{
	const COLLECTION = 'project_dependencies';

	protected function initialize() : void
	{		
		$id_project_dependencies = $this->addField('id_project_dependencies');
		$id_project_dependencies_pattern = Validation::factory('Number', 0);
		$id_project_dependencies_pattern->setMin(0);
		$id_project_dependencies->setPatterns($id_project_dependencies_pattern);
		$id_project_dependencies->addUniqueness(Field::PRIMARY);
		$id_project_dependencies->setProtected();

		$project = new Project();
		$project_field_id_project = $project->getField('id_project');
		$id_project = $this->addFieldClone($project_field_id_project);
		$id_project->setRequired();

		$project_dependencies_url = $this->addField('project_dependencies_url');
		$project_dependencies_url_pattern = Validation::factory('ShowString');
		$project_dependencies_url_pattern->setMin(1);
		$project_dependencies_url->setPatterns($project_dependencies_url_pattern);
		$project_dependencies_url->setRequired();
	}

	protected function after() : void
	{
		$project_dependencies_created = $this->addField('project_dependencies_created');
		$project_dependencies_created_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_dependencies_created->setPatterns($project_dependencies_created_validator);
		$project_dependencies_created->setProtected();
	
		$project_dependencies_updated = $this->addField('project_dependencies_updated');
		$project_dependencies_updated_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_dependencies_updated->setPatterns($project_dependencies_updated_validator);
		$project_dependencies_updated->setProtected();
	}
}
