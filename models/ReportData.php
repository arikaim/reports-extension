<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Core\Utils\DateTime;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Slug;
use Arikaim\Core\Db\Traits\UserRelation;

/**
 * ReportData class
 */
class ReportData extends Model  
{
    const TYPE_SUMMARY         = 0;
    const TYPE_SUMMARY_DAILY   = 1;
    const TYPE_SUMMARY_WEEKLY  = 2;
    const TYPE_SUMMARY_MONTHLY = 3;

    const DATA_TYPE = [
        'summary',
        'summary_daily',
        'summary_weekly',
        'summary_monthly'
    ];

    use Uuid,
        Find,
        UserRelation,
        Slug;      
     
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'report_data';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'uuid',       
        'report_id',
        'field_value',
        'field_name',
        'history_index',
        'user_id',       
        'date_created',
        'date_updated'
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
    

    public function getReportValue($reportId, $userId, $fieldName, $index = 1)
    {

    } 

    public function findReportValue($reportId, $userId, $fieldName, $index = 1)
    {

    } 

    public function saveReportValue($reportId, $reportType, $userId, $fieldName, $fieldValue)
    {
        $currentTime = DateTime::getTimestamp();
        
        switch($reportType) {
            case Self::TYPE_SUMMARY:
            break;
            
            case Self::TYPE_SUMMARY_DAILY: 
            break;
        }


    }
}
