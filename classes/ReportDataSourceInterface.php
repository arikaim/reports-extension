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

/**
 * Report data source interface
 */
interface ReportDataSourceInterface 
{  
    /**
     * Data column name
     *
     * @return string
     */
    public function getDataColumnName(): string;

    /**
     * Get report data
     *
     * @param array $filter
     * @param string $period
     * @param integer|null $day
     * @param integer|null $month
     * @param integer|null $year
     * @return array
     */
    public function getReportData(array $filter, string $period, ?int $day = null, ?int $month = null, ?int $year = null): array;
}
