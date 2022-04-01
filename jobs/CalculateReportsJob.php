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
use Arikaim\Core\Interfaces\Job\RecurringJobInterface;
use Arikaim\Core\Interfaces\Job\JobInterface;
use Arikaim\Core\Utils\DateTime;

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
        $yesterday = DateTime::subInterval('P1D')->getTimestamp();
       
        $errors = 0;
        foreach ($reports as $report) {
            $result = ReportUpdate::updateReport(
                $report,
                \date('j',$yesterday),
                \date('n',$yesterday),
                \date('Y',$yesterday)
            );
            $errors += ($result == false) ? 1 : 0;              
        }

        return ($errors == 0);
    }
}
