<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Classes;


/**
 * Report abstract base class
 */
abstract class Report
{
    /**
     * Report title
     * @var string|null
     */
    protected $title;

    /**
     * Report description
     * @var string|null
     */
    protected $description;

    /**
     * Report daat source
     * @var 
     */
    protected $dataSource;
    
    /**
     *  Report slug
     * 
     * @var string
     */
    protected $slug;


    public function __construct()
    {
        $this->init();
    }

    /**
     * Get report slug
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Init report
     * @return void
     */
    public function init()
    {
    } 

    /**
     * convert to array
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title'         => $this->title,
            'handler_class' => \get_class($this)
        ];
    }

    /**
     * calc report summary data
     * @param int $day
     * @param int $month
     * @param int $year
     * @return void
     */
    abstract public function update(int $day, int $month, int $year);

    protected function slug(string $slug): void
    {
        $this->slug = $slug;
    }

    protected function title(string $title): void
    {
        $this->title = $title;
    }

    protected function description(string $description): void
    {
        $this->description = $description;
    }

    protected function dataSource(string $dataSource): void
    {
        $this->dataSource = $dataSource;
    }
}
