<?PHP

namespace applications\document\output\forms;

use applications\document\output\forms\Matrioska;

class Upsert extends Matrioska {

    protected function initialize()
    {
        parent::initialize();
        
        $this->getField('project_header')->getRow()->setName('header');
        $this->getField('project_footer')->getRow()->setName('footer');
    }
}
