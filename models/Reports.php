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

use Arikaim\Core\Interfaces\Reports\ReportInterface;
use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\DateUpdated;
use Arikaim\Core\Db\Traits\UserRelation;
use Exception;

/**
 * Reports class
 */
class Reports extends Model  
{
    use Uuid,
        Find,
        DateCreated,
        UserRelation,
        DateUpdated,
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
        'user_id',
        'category',
        'editable',
        'data_source',
        'data_filter',
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
     * Delete report data
     *
     * @return boolean
     */
    public function deleteData(): bool
    {
        foreach ($this->fields()->get() as $field) {
            $field->summary()->delete();
        }
        
        return true;
    }

    /**
     * Delete report
     *
     * @return boolean
     */
    public function deleteReport(): bool
    {
        // delete data
        $this->deleteData();
        // delete fields
        $this->fields()->delete();
        // delete report
        $result = $this->delete();

        return ($result !== false);
    } 

    /**
     * Mutator (get) for data_filter attribute.
     *
     * @return array
     */
    public function getDataFilterAttribute()
    {
        return (empty($this->attributes['data_filter']) == true) ? null : \json_decode($this->attributes['data_filter'],true);
    }

    /**
     * Mutator (set) for data_filter attribute.
     *
     * @return void
     */
    public function setDataFilterAttribute($value)
    {
        if (\is_array($value) == true) {
            $this->attributes['data_filter'] = \json_encode($value);
        } else {
            $this->attributes['data_filter'] = $value;
        }       
    }

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
     * Get field
     *
     * @param string|int $name
     * @return Model|null
     */
    public function getField($name)
    {
        $model = $this->fields()->whereHas('id','=',$name)->first();
        if (\is_object($model) == true) {
            return $model;
        }

        return $this->fields()->where('name','=',$name)->first();
    } 

    /**
     * Get summary data
     *   
     * @param string $period
     * @param string|null $fieldType
     * @param string|null $name
     * @param integer|null $month
     * @param integer|null $year
     * @return array
     */
    public function getSummaryData(
        string $period, 
        ?string $fieldType = null, 
        ?string $name = null, 
        ?int $month = null, 
        ?int $year = null
    ): array
    {
        $query = $this->getSummaryQuery($period,$fieldType,$name,$month,$year);

        return (\is_object($query) == true) ? $query->get()->toArray() : [];
    }

    /**
     * Get summary query
     *   
     * @param string $period
     * @param string|null $fieldType
     * @param string|null $name
     * @param integer|null $month
     * @param integer|null $year
     * @return Builder|null
     */
    public function getSummaryQuery(
        string $period, 
        ?string $fieldType = null, 
        ?string $name = null, 
        ?int $month = null, 
        ?int $year = null
    )
    {
        if ((empty($name) == true) && (empty($fieldType) == true)) {
            return null;
        }
        $field = (empty($fieldType) == false) ? $this->fields()->where('type','=',$fieldType) : $this->fields();
        if (empty($name) == false) {
            $field->where('name','=',$name);
        }
        $field = $field->first();
        if (\is_object($field) == false) {
            return null;
        }

        return $field->getSummaryQuery($period,null,$month,$year);
    }

    /**
     * Get report data source instance
     *
     * @param bool $throwException
     * @throws Exception
     * @return ReportInterface|null
     */
    public function getDataSource(bool $throwException = true): ?ReportInterface
    {
        $dataSurceClass = (empty($this->data_source) == true) ? ReportData::class : $this->data_source;
        $dataSouce = new $dataSurceClass();
        if ($dataSouce instanceof ReportInterface) {
            if (\is_array($this->data_filter) == true) {
                $dataSouce->setReportDataFilter($this->data_filter);
            }
           
            return $dataSouce;
        }

        if ($throwException == true) {
            throw new Exception('Not valid report data source');
        }
        
        return null;
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
