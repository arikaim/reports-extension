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

use Arikaim\Extensions\Reports\Classes\ReportInterface;
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
     * @param string $title
     * @param array $details
     * @return bool
     */
    public function create(string $slug, string $title, array $details = []): bool
    {
        $model = Model::Reports('reports');  
        $details['title'] = $title;
        $created = $model->saveReport($slug,$details);

        return \is_object($created);
    }

    /**
     * Add report value
     *
     * @param string $reportSlug
     * @param float|int $value
     * @return boolean
     */
    public function addValue(string $reportSlug, $value): bool
    {
        $model = Model::Reports('reports')->findReport($reportSlug);     
        if (\is_object($model) == false) {
            return false;
        }  
        
        $data = Model::ReportData('reports');

        return $data->addValue($model->id,$value);
    }

    /**
     * Save calculation report field
     *
     * @param string $reportSlug    
     * @param string $type
     * @return boolean
     */
    public function addField(string $reportSlug, string $type): bool
    {
        $model = Model::Reports('reports')->findReport($reportSlug);     
        if (\is_object($model) == false) {
            return false;
        }

        $fields = Model::ReportFields('reports');
       
        return $fields->saveField($model->id,$type);        
    }
    
    public function getValue(string $reportSlug, string $type, string $period)
    {
        $model = Model::Reports('reports');     
    }
}
