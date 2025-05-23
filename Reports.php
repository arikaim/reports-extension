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
        $this->addApiRoute('DELETE','/api/admin/reports/delete/{uuid}','ReportsControlPanel','delete','session'); 
        $this->addApiRoute('DELETE','/api/admin/reports/delete/data/{uuid}','ReportsControlPanel','deleteData','session'); 
        // Api
        $this->addApiRoute('PUT','/api/reports/chart','ReportsApi','readChart','session');       
        // Services
        $this->registerService('Reports');
        // Jobs
        $this->addJob('CalculateReportsJob','calculateReports',true);
        // Console
        $this->registerConsoleCommand('UpdateReportsCommand');  
    }   
    
    /**
     *  Install db tables
     * @return void
     */
    public function dbInstall(): void
    {        
        $this->createDbTable('Reports');
        $this->createDbTable('ReportData');
        $this->createDbTable('ReportFields');
        $this->createDbTable('ReportSummary');
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
