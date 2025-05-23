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
use Arikaim\Extensions\Reports\Models\ReportSummary;
use Arikaim\Core\Interfaces\Reports\ReportFieldInterface;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\DateUpdated;

/**
 * ReportFields class
 */
class ReportFields extends Model implements ReportFieldInterface
{
    use Uuid,
        DateCreated,
        DateUpdated,
        Find;
         
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'report_fields';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'uuid',       
        'report_id',
        'data_column',
        'calc_handler',
        'name',
        'title',
        'type',    
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
     * Get title
     *
     * @return string|null
     */
    public function getTitleAttribute()
    {
        return (empty($this->title) == true) ? $this->name : $this->title;
    } 

    /**
     * Get field calc type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get data column name
     *
     * @return string|null
     */
    public function getDataClumn(): ?string
    {
        return (empty($this->data_column) == true) ? null : $this->data_column;
    }

    /**
     * Report summary values relation
     *
     * @return Relation
    */
    public function summary()
    {
        return $this->hasMany(ReportSummary::class,'field_id');
    }

    /**
     * Get summary data query
     *  
     * @param string $period
     * @param integer|null $day
     * @param integer|null $month
     * @param integer|null $year
     * @return Builder
     */
    public function getSummaryQuery(      
        string $period, 
        ?int $day = null, 
        ?int $month = null, 
        ?int $year = null
    )
    {
        return $this->summary()->where(function ($query) use($period,$day,$month,$year) {
            $query = $query
                ->where('period','=',$period)
                ->where('field_id','=',$this->id);

            if (empty($day) == false) {
                $query = $query->where('day','=',$day);
            }
            if (empty($month) == false) {
                $query = $query->where('month','=',$month);
            }
            if (empty($year) == false) {
                $query = $query->where('year','=',$year);      
            }     

            return $query;
        });
    }

    /**
     * Get summary data
     *
     * @param string $period
     * @param integer|null $day
     * @param integer|null $month
     * @param integer|null $year
     * @return array
     */
    public function getSummaryData(string $period, ?int $day = null, ?int $month = null, ?int $year = null): array 
    {
        $query = $this->getSummaryQuery($period,$day,$month,$year);
        
        return $query->get()->toArray();
    }

    /**
     * Save summary value
     *
     * @param mixed $value
     * @param string $period
     * @param integer $day
     * @param integer $month
     * @param integer $year
     * @return boolean
     */
    public function saveSummaryValue(
        $value, 
        string $period, 
        ?int $day, 
        ?int $month, 
        ?int $year
    ): bool
    {
        $model = $this->getSummaryQuery($period,$day,$month,$year)->first();

        $info = [
            'field_id' => $this->id,
            'value'    => $value,
            'period'   => $period,
            'day'      => $day,
            'month'    => $month,
            'year'     => $year
        ];

        if (\is_object($model) == false) {
            $model = new ReportSummary();       
            $created = $model->create($info);

            return ($created != null);
        } 
        
        return (bool)$model->update($info);       
    }

    /**
     * Scope summary field
     *
     * @param Builder $query
     * @param integer $reportId
     * @param string $type
     * @param string|null $name
     * @return Builder
     */
    public function scopeField($query, int $reportId, string $type, ?string $name = null)
    {
        $query = $query
            ->where('type','=',$type)
            ->where('report_id','=',$reportId);
        if (empty($name) == false) {
            $query->where('name','=',$name);
        }

        return $query;
    }

    /**
     * Return true if field exist
     *
     * @param integer $reportId
     * @param string $type
     * @return boolean
     */
    public function hasField(int $reportId, string $type): bool
    {
        return ($this->field($reportId,$type)->first() != null);
    }

    /**
     * Add or update field
     *
     * @param integer $reportId
     * @param string $type
     * @param string|null $dataColumn
     * @param string|null $name
     * @param string|null $title
     * @return boolean
     */
    public function saveField(
        int $reportId, 
        string $type, 
        ?string $dataColumn,
        ?string $name = null, 
        ?string $title = null
    ): bool
    {
        $model = $this->field($reportId,$type,$name)->first();
        if (\is_object($model) == false) {
            $created = $this->create([
                'report_id'   => $reportId,
                'type'        => $type,
                'data_column' => $dataColumn,
                'name'        => (empty($name) == true) ? null : $name,
                'title'       => $title
            ]);
    
            return ($created != null);
        }   

        return true;
    }
}
