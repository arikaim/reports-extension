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

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\DateUpdated;
use Arikaim\Core\Db\Traits\UserRelation;

/**
 * ReportSummary class
 */
class ReportSummary extends Model  
{
    use Uuid,
        DateCreated,
        DateUpdated,
        UserRelation,
        Find;
         
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'report_summary';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'uuid',  
        'user_id',    
        'field_id',
        'period',
        'day',
        'month',    
        'year',
        'value',        
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
     * Scope summary data values
     *
     * @param Builder $query  
     * @param string $period
     * @param int|null $day
     * @return Builder
     */
    public function scopeSummaryData(
        $query, 
        string $period, 
        ?int $day = null, 
        ?int $month = null, 
        int $year = null
    )
    {
        $query = $query->where('period','=',$period)->where('field_id','=',$this->field_id);
        
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
    }

    /*


    public function hasField(int $reportId, string $type): bool
    {
        return ($this->field($reportId,$type)->first() != null);
    }

   
    public function saveField(int $reportId, string $type): bool
    {
        $model = $this->field($reportId,$type)->first();
        if (\is_object($model) == false) {
            $created = $this->create([
                'report_id' => $reportId,
                'type'      => $type
            ]);
    
            return \is_object($created);
        }   

        return true;
    }
    */
    
}
