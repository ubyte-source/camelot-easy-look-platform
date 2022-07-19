<?PHP

namespace applications\document\output\database\views\project;

use Entity\Validation;

use extensions\entity\Table;

use applications\document\output\database\Project;
use applications\document\output\database\project\CascadeStyleSheet as CCascadeStyleSheet;

class CascadeStyleSheet extends Table
{
    const COLLECTION = 'view_project_cascade_style_sheet';

    protected function initialize()
	{        
		$id_project = new Project();
		$id_project = $id_project->getField('id_project');
		$id_project = $this->addFieldClone($id_project);
		$id_project->setRequired();
        
        $project_cascade_style_sheet = $this->addField(CCascadeStyleSheet::COLLECTION);		
        $project_cascade_style_sheet_pattern_period = new CCascadeStyleSheet();
        $project_cascade_style_sheet_pattern = Validation::factory('Matrioska', $project_cascade_style_sheet_pattern_period);
        $project_cascade_style_sheet_pattern->setMultiple();
		$project_cascade_style_sheet->setPatterns($project_cascade_style_sheet_pattern);
        $project_cascade_style_sheet->setProtected(false);
    }
}
