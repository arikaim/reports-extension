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

use Arikaim\Core\Interfaces\Reports\ReportInterface;

/**
 * Chart report helpers
 */
class ChartReport
{
    /**
     * Get chat axix labels
     *
     * @param string $periodType    
     * @param integer|null $year
     * @return array
     */
    public static function getLabels(string $periodType, ?int $year = null): array
    {
        $year = $year ?? date('Y') - 6;
        switch($periodType) {
            case ReportInterface::CALC_PERIOD_DAILY:
                return \range(1,31);

            case ReportInterface::CALC_PERIOD_MONTHLY:
                $labels = [];
                for ($month = 1; $month <= 12; $month++) {
                    $labels[] = date('F',\mktime(0,0,0,$month,1,date('Y')));               
                }
                return $labels;

            case ReportInterface::CALC_PERIOD_YEARLY:
                return \range($year,($year + 12));
        }

        return [];
    }

    public static function getData(string $periodType, array $summaryData): array
    {
        $result = [];
        for ($day = 1; $day <= 31; $day++) { 
            $item = $summaryData[$day] ?? null;
            $result[] = (\is_array($item) == true) ? (int)$item['value'] ?? 0 : 0;
        }

        return $result;
    }
}
