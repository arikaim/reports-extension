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

/**
 * ReportFields class
 */
class ReportFields extends Model  
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
        'calc_handler',
        'name',
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
     * Scope summary field
     *
     * @param Builder $query
     * @param integer $reportId
     * @param string $type
     * @return Builder
     */
    public function scopeField($query, int $reportId, string $type)
    {
        return $query
            ->where('type','=',$type)
            ->where('report_id','=',$reportId);
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
        $model = $this->field($reportId,$type)->first();

        return \is_object($model);
    }

    /**
     * Add or update field
     *
     * @param integer $reportId
     * @param string $type
     * @return boolean
     */
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
}
