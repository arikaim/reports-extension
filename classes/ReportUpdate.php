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

use Arikaim\Extensions\Reports\Classes\ReportInterface;
use Arikaim\Core\Utils\TimePeriod;

/**
 * Calculate report cron job
 */
class ReportUpdate
{
    /**
     * Update report data
     *
     * @param object|string|int $report
     * @return bool
     */
    public static function updateReport($report): bool
    {
        if (\is_object($report) == false) {
            $report = Model::Reports('reports')->findReport($report);
        }

        if (\is_object($report) == false) {
            return false;
        }

        $errors = 0;
        foreach ($report->fields()->get() as $field) {         
            $result = Self::updateFieldSummary($field,$report);
            $errors += ($result == true) ? 0 : 1;
        }

        return ($errors == 0);
    }

    public static function updateFieldSummary($field, $report): bool
    {
        $dataSource = $report->getDataSource();

        // daily summary      
        $data = $dataSource->getReportData(['report_id' => $field->report_id],'daily',date('d'));
        $summary = Self::calcSummaryValue($data,$field->type);      
        $field->saveSummaryValue($summary,'daily',date('d'),date('m'),date('Y'));  
    
        // monthly summary    
        $data = $field->getSummaryQuery('daily',null,date('m'),date('Y'))->get();
        $summary = Self::calcSummaryValue($data->toArray(),$field->type);     
        $field->saveSummaryValue($summary,'monthly',null,date('m'),date('Y'));   

        // yearly summary    
        $data = $field->getSummaryQuery('monthly',null,null,date('Y'))->get();
        $summary = Self::calcSummaryValue($data->toArray(),$field->type);     
        $field->saveSummaryValue($summary,'yearly',null,null,date('Y'));   

        // all summary    
        $data = $field->getSummaryQuery('yearly',null,null,date('Y'))->get();
        $summary = Self::calcSummaryValue($data->toArray(),$field->type);     
        $field->saveSummaryValue($summary,'all',null,null,null);   

        return true;
    }

    /**
     * Calc report summary value
     *
     * @param array $items
     * @param string $type
     * @return mixed
     */
    public static function calcSummaryValue(array $items, string $type)
    {
        $summary = 0;
        foreach($items as $item) {

            switch ($type) {
                case ReportInterface::CALC_TYPE_COUNT:
                    $summary = $summary + $item['value'];
                    break;
                case ReportInterface::CALC_TYPE_SUM:
                    $summary = $summary + $item['value'];
                    break;               
            }

        }

        return $summary;
    }
}
