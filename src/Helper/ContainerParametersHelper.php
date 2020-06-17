<?php

namespace App\Helper;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContainerParametersHelper {

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * This method returns the root directory of your Symfony project.
     *
     * e.g "/var/www/vhosts/myapplication"
     *
     * @return string
     */
    public function getApplicationRootDir(){
        return $this->params->get('kernel.project_dir');
    }

    /**
     * This method returns the value of the defined parameter.
     *
     * @return mixed
     */
    public function getParameter($parameterName){
        return $this->params->get($parameterName);
    }
}
