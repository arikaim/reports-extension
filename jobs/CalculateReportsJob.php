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

use Arikaim\Core\Interfaces\Job\RecuringJobInterface;
use Arikaim\Core\Interfaces\Job\JobInterface;

/**
 * Calculate reports cron job
 */
class CalculateReportsJob extends CronJob implements RecuringJobInterface, JobInterface
{
    /**
     * Constructor
     *
     * @param string|null $extension
     * @param string|null $name
     * @param integer $priority
     */
    public function __construct($extension = null, $name = null, $priority = 0)
    {
        parent::__construct($extension,$name,$priority);
        
        $this->runEveryHour();
    }

    /**
     * Run job
     *
     * @return mixed
     */
    public function execute()
    {
        $model = Model::Reports('reports');      
        $reports = $model->waitingUpdateQuery()->get();
        
        $result = ReportUpdate::updateReportsData($reports);

        return $result;
    }
}
