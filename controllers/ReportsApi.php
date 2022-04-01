<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ApiController;
use Arikaim\Extensions\Reports\Classes\ChartReport;

/**
 * Reports api controler
*/
class ReportsApi extends ApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {       
    }
    
    /**
     * Load chart report
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function readChartController($request, $response, $data) 
    {    
        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $period = $data->getString('period','daily');
            $type = $data->getString('type','line');
            $month = $data->getString('month',null);
            $year = $data->getString('year',null);
            $fieldName = $data->getString('field_name',null);
            $fieldType = $data->getString('field_type',null);

            $report = Model::Reports('reports')->findById($uuid); 
            $summary = $report->getSummaryQuery($period,$fieldType,$fieldName,$month,$year);
            $summary = (\is_object($summary) == true) ? $summary->get()->keyBy('day')->toArray() : [];         
            $data = ChartReport::getData($period,$summary);
            $labels = ChartReport::getLabels($period);

            $chartConfig = [
                'type' => $type,
                'data' => [
                    'labels'   => $labels,
                    'datasets' => [[
                        'tension'     => 0.1,
                        'data'        => $data,
                        'borderColor' => 'rgb(255,99,132)',
                        'fill'        => false
                    ]]
                ],    
                'options' => [
                    'responsive' => true,
                    'plugins' => [
                        'legend' => false,
                    ]
                ]
            ];

            $this->setResponse(\is_object($report),function() use($chartConfig,$period,$month,$year) {                
                $this
                    ->message('read')
                    ->field('period',$period)
                    ->field('month',$month)
                    ->field('year',$year)
                    ->field('chart',$chartConfig);   
            },'errors.update');
        });
        $data->validate();    
    }
}
