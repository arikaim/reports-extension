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
 * Report interface
 */
interface ReportInterface 
{  
    const CALC_TYPE_AVG    = 'avg';
    const CALC_TYPE_SUM    = 'sum';
    const CALC_TYPE_COUNT  = 'count';
    const CALC_TYPE_CUSTOM = 'custom';

    const CALC_PERIOD_DAILY   = 'daily';
    const CALC_PERIOD_WEEKLY  = 'weekly';
    const CALC_PERIOD_MONTHLY = 'monthly';
    const CALC_PERIOD_YEARLY  = 'yearly';
   
    /**
     * Get calculated report value
     *
     * @param array $params
     * @return mixed
     */
    public function getSummary(array $params = []);
}
