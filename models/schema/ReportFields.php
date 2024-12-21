<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Reports\Models\Schema;

use Arikaim\Core\Db\Schema;

/**
 * Report fields db table
 */
class ReportFields extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'report_fields';

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {            
        // columns    
        $table->id();
        $table->prototype('uuid'); 
        $table->relation('report_id','reports');       
        $table->string('type')->nullable(false);
        $table->string('name')->nullable(true);
        $table->string('data_column')->nullable(true);
        $table->string('title')->nullable(true);
        $table->string('calc_handler')->nullable(true);
        $table->dateCreated();
        $table->dateUpdated();
        // unique index 
        $table->unique(['report_id','type','name']);           
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {       
    }
}
