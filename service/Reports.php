<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Service;

use Psr\Container\ContainerInterface;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Service\Service;
use Arikaim\Core\Service\ServiceInterface;

/**
 * Reports service class
*/
class Reports extends Service implements ServiceInterface
{
    /**
     * Constructor
     */
    public function __construct(?ContainerInterface $container = null)
    {
        $this->setServiceName('reports');

        parent::__construct($container);
    }

    /**
     * Create report
     *
     * @param string $slug
     * @param string|null $dataSource
     * @param string|null $title
     * @param int|null $userId
     * @return bool
     */
    public function create(
        string $slug, 
        ?string $dataSource = null, 
        ?string $title = null, 
        ?int $userId = null,
        $dataSourceFilter = null
    ): bool
    {
        $model = Model::Reports('reports');  
        $filter = (\is_array($dataSourceFilter) == true) ? \json_encode($dataSourceFilter) : $dataSourceFilter;
        
        $details = [
            'title'         => $title ?? $slug,
            'data_source'   => $dataSource,
            'data_filter'   => $filter
        ];

        $created = $model->saveReport($slug,$details,$userId);

        return \is_object($created);
    }

    /**
     * Add report value
     *
     * @param string $reportSlug
     * @param float|int $value
     * @param string|null $fieldName
     * @return boolean
     */
    public function addValue(string $reportSlug, $value, ?string $fieldName = null): bool
    {
        $model = Model::Reports('reports');
        $report = $model->findReport($reportSlug);     
        if (\is_object($report) == false) {
            return false;                  
        }  
        
        $data = Model::ReportData('reports');

        return $data->addValue($report->id,$value,$fieldName);
    }

    /**
     * Save calculation report field
     *
     * @param string $reportSlug    
     * @param string $type
     * @param string|null $dataColumn
     * @param string|null $name
     * @param string|null $title
     * @return boolean
     */
    public function addField(
        string $reportSlug,
        ?int $userId, 
        string $type, 
        ?string $dataColumn, 
        ?string $name = null, 
        ?string $title = null
    ): bool
    {
        $report = Model::Reports('reports')->findReport($reportSlug,$userId);     
        if (\is_object($report) == false) {
            return false;
        }

        return Model::ReportFields('reports')->saveField($report->id,$type,$dataColumn,$name,$title);           
    }
    
    public function getValue(string $reportSlug, string $type, string $period)
    {
        $model = Model::Reports('reports');     
    }
}
