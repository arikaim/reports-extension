<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Subscribers;

use Arikaim\Core\Db\Model;
use Arikaim\Extensions\Reports\Classes\ReportUpdate;
use Arikaim\Core\Events\EventSubscriber;
use Arikaim\Core\Interfaces\Events\EventSubscriberInterface;

/**
 * Update reports data values
*/
class UpdateReportsSubscriber extends EventSubscriber implements EventSubscriberInterface
{
    /**
     * Constructor
     */
    public function __construct() 
    {
        $this->subscribe('*');
    }

    /**
     * Run 
     *
     * @param EventInterface $event
     * @return void
     */
    public function execute($event)
    {
      //  $model = Model::Reports('reports');      
     //   $reports = $model->subscribedQuery()->get();
        
     //   $result = ReportUpdate::updateReportsData($reports);

        return true;
    }
}
