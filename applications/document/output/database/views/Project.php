<?PHP

namespace applications\document\output\database\views;

use extensions\entity\Table;

use applications\document\output\database\Project as Map;
use applications\document\output\database\views\project\Dependencies;
use applications\document\output\database\views\project\HyperTextMarkupLanguage;
use applications\document\output\database\views\project\Javascript;
use applications\document\output\database\views\project\CascadeStyleSheet;

class Project extends Map
{
    const COLLECTION = 'view_project';

    protected function initialize()
	{        
        parent::initialize();

        $project_depndencies = new Dependencies();
		$project_depndencies = $project_depndencies->getField(\applications\document\output\database\project\Dependencies::COLLECTION);
		$project_depndencies = $this->addFieldClone($project_depndencies);

        $project_hyper_text_markup_language = new HyperTextMarkupLanguage();
		$project_hyper_text_markup_language = $project_hyper_text_markup_language->getField(\applications\document\output\database\project\HyperTextMarkupLanguage::COLLECTION);
		$project_hyper_text_markup_language = $this->addFieldClone($project_hyper_text_markup_language);

        $project_javascript = new Javascript();
		$project_javascript = $project_javascript->getField(\applications\document\output\database\project\Javascript::COLLECTION);
		$project_javascript = $this->addFieldClone($project_javascript);

        $project_cascade_style_sheet = new CascadeStyleSheet();
		$project_cascade_style_sheet = $project_cascade_style_sheet->getField(\applications\document\output\database\project\CascadeStyleSheet::COLLECTION);
		$project_cascade_style_sheet = $this->addFieldClone($project_cascade_style_sheet);
    }
}

