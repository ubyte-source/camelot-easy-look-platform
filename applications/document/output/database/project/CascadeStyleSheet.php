<?PHP

namespace applications\document\output\database\project;

use Entity\Field;
use Entity\Validation;

use extensions\entity\Table;

use applications\document\output\database\Project;

class CascadeStyleSheet extends Table
{
	const COLLECTION = 'project_cascade_style_sheet';

	protected function initialize() : void
	{		
		$id_project_cascade_style_sheet = $this->addField('id_project_cascade_style_sheet');
		$id_project_cascade_style_sheet_pattern = Validation::factory('Number', 0);
		$id_project_cascade_style_sheet_pattern->setMin(0);
		$id_project_cascade_style_sheet->setPatterns($id_project_cascade_style_sheet_pattern);
		$id_project_cascade_style_sheet->addUniqueness(Field::PRIMARY);
		$id_project_cascade_style_sheet->setProtected();

		$project = new Project();
		$project_field_id_project = $project->getField('id_project');
		$id_project = $this->addFieldClone($project_field_id_project);
		$id_project->setRequired();

		$project_cascade_style_sheet_text = $this->addField('project_cascade_style_sheet_text');
		$project_cascade_style_sheet_text_pattern = Validation::factory('Textarea');
		$project_cascade_style_sheet_text_pattern->setMin(1);
		$project_cascade_style_sheet_text->setPatterns($project_cascade_style_sheet_text_pattern);
	}

	protected function after() : void
	{
		$project_cascade_style_sheet_created = $this->addField('project_cascade_style_sheet_created');
		$project_cascade_style_sheet_created_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_cascade_style_sheet_created->setPatterns($project_cascade_style_sheet_created_validator);
		$project_cascade_style_sheet_created->setProtected();
	
		$project_cascade_style_sheet_updated = $this->addField('project_cascade_style_sheet_updated');
		$project_cascade_style_sheet_updated_validator = Validation::factory('DateTime', null, 'd-m-Y H:i:s', 'Y-m-d H:i:s.u');
		$project_cascade_style_sheet_updated->setPatterns($project_cascade_style_sheet_updated_validator);
		$project_cascade_style_sheet_updated->setProtected();
	}
}
