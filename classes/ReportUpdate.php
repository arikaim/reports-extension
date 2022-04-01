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
use Arikaim\Core\Interfaces\Reports\ReportInterface;
use Arikaim\Core\Interfaces\Reports\ReportFieldInterface;

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
    public static function updateReport(
        $report, 
        ?int $day = null, 
        ?int $month = null, 
        ?int $year = null
    ): bool
    {
        if (\is_object($report) == false) {
            $report = Model::Reports('reports')->findReport($report);
        }
        if (\is_object($report) == false) {
            return false;
        }

        $errors = 0;
    
        $data = $report->getDataSource()->getReportData('daily',$day,$month,2022);
        foreach ($report->fields()->get() as $field) {         
            $result = Self::updateFieldSummary($field,$data,$day,$month,$year);
            $errors += ($result == true) ? 0 : 1;
        }
    
        $report->setDateUpdated();

        return ($errors == 0);
    }

    /**
     * Update field summary
     *
     * @param ReportFieldInterface $field
     * @param object $dataSource
     * @param integer|null $day
     * @param integer|null $month
     * @param integer|null $year
     * @return boolean
     */
    public static function updateFieldSummary(
        ReportFieldInterface $field, 
        array $data, 
        ?int $day = null, 
        ?int $month = null, 
        ?int $year = null
    ): bool
    {        
        $day = $day ?? \date('j');
        $month = $month ?? \date('n');
        $year = $year ?? \date('Y');
    
        // daily summary        
        $summary = Self::calcSummaryValue($data,$field,ReportInterface::CALC_PERIOD_DAILY);  
        $field->saveSummaryValue($summary,ReportInterface::CALC_PERIOD_DAILY,$day,$month,$year);  
    
        // monthly summary    
        $data = $field->getSummaryData(ReportInterface::CALC_PERIOD_DAILY,null,$month,$year);
        $summary = Self::calcSummaryValue($data,$field,ReportInterface::CALC_PERIOD_MONTHLY);     
        $field->saveSummaryValue($summary,ReportInterface::CALC_PERIOD_MONTHLY,null,$month,$year);   

        // yearly summary    
        $data = $field->getSummaryData(ReportInterface::CALC_PERIOD_MONTHLY,null,null,$year);
        $summary = Self::calcSummaryValue($data,$field,ReportInterface::CALC_PERIOD_YEARLY);           
        $field->saveSummaryValue($summary,ReportInterface::CALC_PERIOD_YEARLY,null,null,$year);   

        // all summary    
        $data = $field->getSummaryData(ReportInterface::CALC_PERIOD_YEARLY,null,null,$year);
        $summary = Self::calcSummaryValue($data,$field,ReportInterface::CALC_PERIOD_ALL);     
        $field->saveSummaryValue($summary,ReportInterface::CALC_PERIOD_ALL,null,null,null);   

        return true;
    }

    /**
     * Calc report summary value
     
     * @param array $items
     * @param ReportFieldInterface $field   
     * @param string $period
     * @return mixed
     */
    public static function calcSummaryValue(
        array $items, 
        ReportFieldInterface $field,
        string $period
    )
    {
        $dataColumn = ($period == ReportInterface::CALC_PERIOD_DAILY) ? $field->getDataClumn() : 'value';
        $values = (empty($dataColumn) == false) ? \array_column($items,$dataColumn) : $items;
       
        switch ($field->getType()) {
            case ReportInterface::CALC_TYPE_AVG:
                $summary = \array_sum($values);
                $summary = ($summary / \count($values));
                break;
            
            case ReportInterface::CALC_TYPE_SINGLE_VALUE:
                $summary = \end($values);
                break;

            case ReportInterface::CALC_TYPE_SUM:
                $summary = \array_sum($values);
                break;
            
            case ReportInterface::CALC_TYPE_COUNT:
                $summary = ($period == ReportInterface::CALC_PERIOD_DAILY) ? \count($values) : \array_sum($values);
                break;

            default:
                $summary = \array_sum($values);
                break;
        }
       
        return $summary;
    }
}
