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
        $table->userId();    
        $table->string('title')->nullable(false);
        $table->slug(false);
        $table->integer('editable')->nullable(true); 
        $table->integer('public')->nullable(true); 
        $table->string('category')->nullable(true);   
        $table->string('extension_name')->nullable(true);
        $table->string('data_source')->nullable(true);     
        $table->text('description')->nullable(true);
        $table->dateCreated();
        $table->dateUpdated();         
        // index
        $table->index('extension_name');
        $table->unique(['slug','user_id']);
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
