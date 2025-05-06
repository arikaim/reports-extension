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

use Arikaim\Core\Interfaces\Reports\ReportInterface;
use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\ReportData as ReportDataTrait;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\UserRelation;

/**
 * ReportData class
 */
class ReportData extends Model implements ReportInterface
{
    use Uuid,
        ReportDataTrait,
        DateCreated,
        UserRelation,
        Find;
         
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
        'user_id',
        'report_id',
        'field_name',
        'value',    
        'date_created'
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * Add report value
     *
     * @param integer $reportId
     * @param mixed $value
     * @param string|null $fieldName
     * @param int|null $userId
     * @return boolean
     */
    public function addValue(
        int $reportId, 
        $value, 
        ?string $fieldName = null,
        ?int $userId = null
        ): bool
    {
        $model = $this->create([            
            'report_id'  => $reportId,
            'value'      => $value,
            'field_name' => (empty($fieldName) == true) ? null : $fieldName,
            'user_id'    => $userId
        ]);

        return ($model !== null);
    } 
}
