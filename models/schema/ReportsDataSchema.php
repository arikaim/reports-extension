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
 * Report data db table
 */
class ReportsDataSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'report_data';

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
        $table->userId();  
        $table->relation('report_id','reports');
        $table->string('field_name')->nullable(false);
        $table->price(0,'field_value');
        $table->integer('history_index')->nullable(false)->default(1);       
        $table->dateCreated();
        $table->dateUpdated();

        // index     
        $table->unique(['user_id','report_id','field_name']); 
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
