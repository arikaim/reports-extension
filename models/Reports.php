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
use Arikaim\Extensions\Reports\Models\ReportFields;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\DateCreated;

/**
 * Reports class
 */
class Reports extends Model  
{
    use Uuid,
        Find,
        DateCreated,
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
        'public',
        'description',
        'slug',
        'category',
        'editable',
        'data_source',
        'extension_name',
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

    /**
     * Report summary fields relation
     *
     * @return Relation
     */
    public function fields()
    {
        return $this->hasMany(ReportFields::class,'report_id');
    }

    /**
     * Get summary data
     *
     * @param string $fieldType
     * @param string $period
     * @param integer|null $month
     * @param integer|null $year
     * @return array
     */
    public function getSummaryData(string $fieldType, string $period, ?int $month = null, ?int $year = null): array
    {
        $model = $this->fields()->where('type','=',$fieldType)->first();
        if (\is_object($model) == false) {
            return [];
        }

        return $model->getSummary($period,null,$month,$year);
    }

    /**
     * Get report data source instance
     *
     * @return object
     */
    public function getDataSource()
    {
        $dataSurceClass = (empty($this->data_source) == true) ? ReportData::class : $this->data_source;
       
        return new $dataSurceClass();
    }

    /**
     * Report scope
     *
     * @param Builder $query
     * @param string $slug
     * @param integer|null $userId
     * @return Builder
     */
    public function scopeReport($query, string $slug, ?int $userId = null)
    {
        $query = $query->where('slug','=',$slug);
        if (empty($userId) == false) {
            $query = $query->where('user_id','=',$userId);
        }

        return $query;
    }

    /**
     * Get report model
     *
     * @param string $slug
     * @param integer|null $userId
     * @return Model|null
     */
    public function findReport(string $slug, ?int $userId = null)
    {
        $query = $this->report($slug,$userId);
        
        return $query->first();
    } 

    /**
     * Return true if report exist
     *
     * @param string $slug
     * @param integer|null $userId
     * @return boolean
     */
    public function hasReport(string $slug, ?int $userId = null): bool
    {
        return \is_object($this->findReport($slug,$userId));
    }

    /**
     * Save report
     *
     * @param string $slug
     * @param array $data
     * @param integer|null $userId
     * @return Model|null
     */
    public function saveReport(string $slug, array $data, ?int $userId = null)
    {
        $model = $this->findReport($slug,$userId);
        $data['user_id'] = $userId;
        $data['slug'] = $slug;

        if (\is_object($model) == true) {
            $model->update($data);
            return $model;
        }
        
        return $this->create($data);      
    }
}
