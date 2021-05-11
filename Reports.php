<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports;

use Arikaim\Core\Extension\Extension;

/**
 * Reports extension
*/
class Reports extends Extension
{
    /**
     * Install extension routes, events, jobs ..
     *
     * @return void
    */
    public function install()
    {
        // Control Panel
        $this->addApiRoute('POST','/api/admin/reports/add','ReportsControlPanel','add','session'); 
        $this->addApiRoute('PUT','/api/admin/reports/update','ReportsControlPanel','update','session'); 
        $this->addApiRoute('PUT','/api/admin/reports/status','ReportsControlPanel','setStatus','session'); 
      
        // Create db tables
        $this->createDbTable('ReportsSchema');
        $this->createDbTable('ReportDataSchema');
        $this->createDbTable('ReportFieldsSchema');
        // Services
        $this->registerService('Reports');
    }   
    
    /**
     * UnInstall extension
     *
     * @return void
     */
    public function unInstall()
    {  
    }
}
