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
 * Reports db table
 */
class ReportsSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'reports';

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
        $table->status();    
        $table->string('title')->nullable(false);
        $table->slug();
        $table->string('update_interval',50)->nullable(true);    
        $table->string('event_name')->nullable(true);   
        $table->string('extension_name')->nullable(true);
        $table->string('data_source')->nullable(false);      
        $table->integer('data_type')->nullable(false);

        $table->dateCreated();
        $table->dateUpdated();
         
        // index
        $table->index('extension_name');
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
