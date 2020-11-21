<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Interfaces;

/**
 * Report interface
 */
interface ReportInterface 
{  
    /**
     * Get calculated report data value
     *
     * @param array $params
     * @return mixed
     */
    public function getReportData(array $params = []);
}
