<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ControlPanelApiController;
use Arikaim\Core\Controllers\Traits\Status;

/**
 * Reports control panel controler
*/
class ReportsControlPanel extends ControlPanelApiController
{
    use Status;

    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('reports::admin.messages');

        $this->setExtensionName('reports');
        $this->setModelClass('Reports');
    }

    /**
     * Delete report
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteController($request, $response, $data) 
    {    
        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $report = Model::Reports('reports')->findById($uuid); 
        });
        $data->validate();    
    }

    /**
     * Delete report data
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteDataController($request, $response, $data) 
    {    
        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $report = Model::Reports('reports')->findById($uuid); 

            $result = $report->deleteData();

            exit();
            
            $this->setResponse($result,function() use($uuid) {                
                $this
                    ->message('delete_data')
                    ->field('uuid',$uuid);                    
            },'errors.update');
        });
        $data->validate();    
    }
}
