<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Jobs;

use Arikaim\Core\Queue\Jobs\CronJob;
use Arikaim\Core\Db\Model;
use Arikaim\Extensions\Reports\Classes\ReportUpdate;
use Arikaim\Extensions\Reports\Classes\ReportInterface;


use Arikaim\Core\Utils\TimePeriod;
use Arikaim\Core\Interfaces\Job\RecurringJobInterface;
use Arikaim\Core\Interfaces\Job\JobInterface;

/**
 * Calculate reports cron job
 */
class CalculateReportsJob extends CronJob implements RecurringJobInterface, JobInterface
{
    /**
     * Constructor
     *
     * @param string|null $extension
     * @param string|null $name
     * @param array $params
     */
    public function __construct(?string $extension = null, ?string $name = null, array $params = [])
    {
        parent::__construct($extension,$name,$params);
        
        $this->runEveryDay();
    }

    /**
     * Run job
     *
     * @return mixed
     */
    public function execute()
    {
        $reports = Model::Reports('reports')->activeQuery()->get();
       
        $errors = 0;
        foreach ($reports as $report) {
            $result = ReportUpdate::updateReport($report);
            $errors += ($result == false) ? 1 : 0;              
        }

        return ($errors == 0);
    }
}
