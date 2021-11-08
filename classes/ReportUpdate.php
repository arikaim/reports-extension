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

use Arikaim\Core\Db\Model;
use Arikaim\Extensions\Reports\Classes\ReportInterface;

/**
 * Calculate report cron job
 */
class ReportUpdate
{
    /**
     * Update report data
     *
     * @param object|string|int $report
     * @param integer|null $day
     * @param integer|null $month
     * @param integer|null $year
     * @return bool
     */
    public static function updateReport($report, ?int $day = null, ?int $month = null, ?int $year = null): bool
    {
        if (\is_object($report) == false) {
            $report = Model::Reports('reports')->findReport($report);
        }

        if (\is_object($report) == false) {
            return false;
        }

        $errors = 0;
        $dataSource = $report->getDataSource();
        foreach ($report->fields()->get() as $field) {         
            $result = Self::updateFieldSummary($field,$dataSource,$day,$month,$year);
            $errors += ($result == true) ? 0 : 1;
        }
    
        $report->setDateUpdated();

        return ($errors == 0);
    }

    /**
     * Undocumented function
     *
     * @param object $field
     * @param object $dataSource
     * @param integer|null $day
     * @param integer|null $month
     * @param integer|null $year
     * @return boolean
     */
    public static function updateFieldSummary($field, $dataSource, ?int $day = null, ?int $month = null, ?int $year = null): bool
    {        
        $day = $day ?? date('d');
        $month = $month ?? date('n');
        $year = $year ?? date('Y');
        $dataColumn = $dataSource->getDataColumnName();

        // daily summary      
        $data = $dataSource->getReportData([
            'report_id'  => $field->report_id,
            'field_name' => $field->name
        ],'daily',$day);
        $summary = Self::calcSummaryValue($data,$field,$dataColumn);   
        $field->saveSummaryValue($summary,'daily',$day,$month,$year);  
    
        // monthly summary    
        $data = $field->getSummaryQuery('daily',null,$month,$year)->get();
        $summary = Self::calcSummaryValue($data->toArray(),$field,$dataColumn);     
        $field->saveSummaryValue($summary,'monthly',null,$month,$year);   

        // yearly summary    
        $data = $field->getSummaryQuery('monthly',null,null,$year)->get();
        $summary = Self::calcSummaryValue($data->toArray(),$field,$dataColumn);     
        $field->saveSummaryValue($summary,'yearly',null,null,$year);   

        // all summary    
        $data = $field->getSummaryQuery('yearly',null,null,$year)->get();
        $summary = Self::calcSummaryValue($data->toArray(),$field,$dataColumn);     
        $field->saveSummaryValue($summary,'all',null,null,null);   

        return true;
    }

    /**
     * Calc report summary value
     *
     * @param array $items
     * @param object $field
     * @param string $dataColumn
     * @return mixed
     */
    public static function calcSummaryValue(array $items, $field, string $dataColumn)
    {
        $values = \array_column($items,$dataColumn);
        $summary = \array_sum($values);
       
        if ($field->type == ReportInterface::CALC_TYPE_AVG) {
            $summary = ($summary / \count($values));
        }

        if ($field->type == ReportInterface::CALC_TYPE_SINGLE_VALUE) {
            $summary = \end($values);          
        }

        return $summary;
    }
}
