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
class ReportData extends Schema  
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
        $table->userId(true);    
        $table->relation('report_id','reports');
        $table->string('field_name')->nullable(true);
        $table->decimal('value',15,4)->nullable(false);     
        $table->dateCreated();
        // index            
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
