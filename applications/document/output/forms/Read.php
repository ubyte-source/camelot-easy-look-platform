<?PHP

namespace applications\document\output\forms;

use IAM\Sso;
use IAM\Gateway;

use Knight\armor\Output;

use Entity\map\Remote;

use applications\document\output\database\Project;

class Read extends Project
{
    protected function initialize() : void
    {
        parent::initialize();

        $this->getField('id_project_application')->setProtected(true);
        $this->getField('project_header')->setProtected(true);
        $this->getField('project_footer')->setProtected(true);
    }

    protected function after() : void
	{
        parent::after();

        if (Sso::youHaveNoPolicies('sso/application/action/read')) return;

        $application = new Remote($this, 'iam', 'sso/application');
        $application->setStructure(function () {
            $parameters = $this->getParameters();
            return Gateway::getStructure($parameters[0], $parameters[1]);
        });
        $application->getData()->setKey($this->getField('id_project_application')->getName());
        $application->getData()->setWorker(function ($post) : array {
            $request_parameters = $this->getRemote()->getParameters();
            $request = Gateway::callAPI($request_parameters[0], 'sso/application/read', $post);
            return $request->{Output::APIDATA};
        });
        $this->addRemote($application);
    }
}
