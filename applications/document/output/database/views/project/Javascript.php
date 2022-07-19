<?PHP

namespace applications\document\output\database\views\project;

use Entity\Validation;

use extensions\entity\Table;

use applications\document\output\database\Project;
use applications\document\output\database\project\Javascript as CJavascript;

class Javascript extends Table
{
    const COLLECTION = 'view_project_javascript';

    protected function initialize()
	{        
		$id_project = new Project();
		$id_project = $id_project->getField('id_project');
		$id_project = $this->addFieldClone($id_project);
		$id_project->setRequired();
        
        $project_javascript = $this->addField(CJavascript::COLLECTION);		
        $project_javascript_pattern_period = new CJavascript();
        $project_javascript_pattern = Validation::factory('Matrioska', $project_javascript_pattern_period);
        $project_javascript_pattern->setMultiple();
		$project_javascript->setPatterns($project_javascript_pattern);
        $project_javascript->setProtected(false);
    }
}
