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
 * Report summary values db table
 */
class ReportSummarySchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'report_summary';

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
        $table->relation('field_id','report_fields');       
        $table->string('period')->nullable(false);
        $table->integer('day')->nullable(true);
        $table->integer('month')->nullable(true);
        $table->integer('year')->nullable(true);
        $table->decimal('value',15,4)->nullable(true)->default(0.00);     
        $table->dateCreated();
        $table->dateUpdated();
        // index 
        $table->unique(['field_id','period','day','month','year']);           
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
