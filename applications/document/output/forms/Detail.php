<?PHP

namespace applications\document\output\forms;

use IAM\Sso;
use IAM\Gateway;

use Knight\armor\Output;

use Entity\Field;
use Entity\Validation;

use applications\document\output\database\views\Project;

class Detail extends Project
{
    protected function initialize()
	{
        parent::initialize();

        IAMRequest::setOverload(
            'sso/application/action/detail'
        );

        $id_project_application = $this->getField('id_project_application');
        $id_project_application_pattern = Validation::factory('ShowString');
        $id_project_application_pattern->setClosureMagic(function (Field $field) {
            $field_readmode = $field->getReadMode();
            if (false === $field_readmode) return true;

            $field_value = $field->getValue();
            $field_value = Gateway::callAPI('iam', 'sso/application/detail' . chr(47) . $field_value);
            if (property_exists($field_value, Output::APIDATA)){
                $application = $field->getCore()->addField('application');
                $application->setValue($field_value->{Output::APIDATA});
            }
            return true;
        });
        $id_project_application->setPatterns($id_project_application_pattern);
    }
}
