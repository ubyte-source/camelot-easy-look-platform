<?PHP

namespace applications\document\output\database\views\project;

use Entity\Validation;

use extensions\entity\Table;

use applications\document\output\database\Project;
use applications\document\output\database\project\Dependencies as CDependencies;

class Dependencies extends Table
{
    const COLLECTION = 'view_project_dependencies';

    protected function initialize()
	{        
		$id_project = new Project();
		$id_project = $id_project->getField('id_project');
		$id_project = $this->addFieldClone($id_project);
		$id_project->setRequired();
        
        $project_dependencies = $this->addField(CDependencies::COLLECTION);		
        $project_dependencies_pattern_period = new CDependencies();
        $project_dependencies_pattern = Validation::factory('Matrioska', $project_dependencies_pattern_period);
        $project_dependencies_pattern->setMultiple();
		$project_dependencies->setPatterns($project_dependencies_pattern);
        $project_dependencies->setProtected(false);
    }
}
