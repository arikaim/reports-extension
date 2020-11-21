<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Classes;

use Arikaim\Core\Utils\Factory;
use Arikaim\Core\Db\Model;

/**
 * Calculate report cron job
 */
class ReportUpdate 
{
    /**
     * Update reports data
     *
     * @return mixed
     */
    public static function updateReportsData($reports)
    {
        foreach ($reports as $report) {
            $result = Self::updateReport($report);            
        }
    }

    /**
     * Update report data
     *
     * @param object $report
     * @return void
     */
    public static function updateReport($report)
    {
        $dataSoure = $report->data_source;
        $instance = Factory::createInstance($dataSoure);
        $data = null;
        if (empty($instance) == false) {
            // get value from data source
            if (\is_callable([$instance,'getReportData']) == true) {
                $data = $instance->getReportData();
            } elseif (\is_callable($instance) == true) {
                $data = $instance();
            }
        }
        // save data
        $result = (empty($data) == false) ? $report->saveReportData($data) : false;
        
        return $result;
    }
}
