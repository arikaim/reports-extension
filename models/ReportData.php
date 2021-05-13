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

use Arikaim\Extensions\Reports\Classes\ReportInterface;
use Arikaim\Extensions\Reports\Classes\ReportDataSourceInterface;
use Arikaim\Core\Utils\TimePeriod;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\DateCreated;

/**
 * ReportData class
 */
class ReportData extends Model implements ReportDataSourceInterface
{
    use Uuid,
        DateCreated,
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
        'report_id',
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
     * @return boolean
     */
    public function addValue(int $reportId, $value): bool
    {
        $model = $this->create([            
            'report_id' => $reportId,
            'value'     => $value
        ]);

        return \is_object($model);
    } 

    /**
     * Report data scope query
     *
     * @param Builder $query
     * @param integer $reportId
     * @return Builder
     */
    public function scopeReportDataQuery($query, int $reportId)
    {
        return $query->where('report_id','=',$reportId);
    }

    /**
     * Data scope per period
     *
     * @param Builder $query
     * @param integer $reportId
     * @param string $periodType
     * @param integer|null $day
     * @param integer|null $month
     * @param integer|null $year
     * @return Builder
     */
    public function scopeDataQuery(
        $query,
        int $reportId, 
        string $periodType, 
        ?int $day = null, 
        ?int $month = null, 
        ?int $year = null
    )
    {
        switch ($periodType) {
            case ReportInterface::CALC_PERIOD_DAILY:      
                $period = TimePeriod::getDayPeriod($day,$month,$year);     
                break;
        
            case ReportInterface::CALC_PERIOD_MONTHLY:     
                $period = TimePeriod::getMonthPeriod($month,$year);       
                break;
    
            case ReportInterface::CALC_PERIOD_YEARLY:   
                $period = TimePeriod::getYearPeriod($month,$year);       
                break;           
        }

        return $query
                    ->where('report_id','=',$reportId)
                    ->where('date_created','>=',$period['start'])
                    ->where('date_created','<=',$period['end']);
    } 

    /**
     * Get report data
     *
     * @param array $filter
     * @param string $period
     * @param integer|null $day
     * @param integer|null $month
     * @param integer|null $year
     * @return array
     */
    public function getReportData(array $filter, string $period, ?int $day = null, ?int $month = null, ?int $year = null): array
    {
        $reportId = $filter['report_id'] ?? $this->report_id;

        return $this->dataQuery($reportId,$period,$day,$month,$year)->get()->toArray();
    }

    /**
     * Data column name
     *
     * @return string
     */
    public function getDataColumnName(): string
    {
        return 'value';
    }
}
