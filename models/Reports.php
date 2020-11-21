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
use Arikaim\Extensions\Reports\Models\ReportData;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Slug;
use Arikaim\Core\Db\Traits\Status;

/**
 * Reports class
 */
class Reports extends Model  
{
    use Uuid,
        Find,
        Slug,
        Status;
     
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'reports';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'uuid',       
        'status',
        'title',
        'slug',
        'extension_name',
        'update_interval',
        'event_name',
        'data_source',
        'data_type',
        'date_created',
        'date_updated'
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * Report data relation
     *
     * @return Relation
     */
    public function data()
    {
        return $this->hasMany(ReportData::class,'report_id');
    }

    public function saveReportData($data, $reportId = null)
    {
        $model = (empty($reportId) == true) ? $this : $this->findById($reportId);
        $reportData = new ReportData();

       
    } 

    /**
     * Get pending update reports
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWaitingUpdateQuery($query)
    {
        $query = $query->where('status','=',1)->whereNotNull('update_interval');

        return $query;
    }

    /**
     * Get update by event reprts
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeSubscribedQuery($query, $eventName)
    {
        return $query->where('status','=',1)->where('event_name','=',$eventName);
    }

    /**
     * Save report
     *
     * @param string $title
     * @param int $type
     * @param string $dataSource
     * @param string|null $extension
     * @return Model|bool
     */
    public function saveReport($title, $type, $dataSource, $updateInterval = null, $extension = null)
    {
        $report = $this->findBySlug($title);
        $data = [
            'title'           => $title,
            'type'            => $type,
            'data_source'     => $dataSource,
            'update_interval' => $updateInterval,
            'extension_name'  => $extension
        ];
        
        return (\is_object($report) == true) ? $report->update($data) : $report->create($data);      
    }
}
