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
        $fields = Model::ReportFields('reports')->get();      
        
        foreach ($fields as $field) {
            $result = $this->calcField($field);            
        }

        return $result;
    }

    /**
     * Update report fields
     *
     * @param object $field
     * @return void
     */
    public function calcField($field)
    {
        // update daily value

    }
}
