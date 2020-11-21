/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ReportsAdmin() {
    
  

    this.update = function(formId, onSuccess, onError) {
        return arikaim.put('/api/users/admin/update',formId,onSuccess,onError);         
    };
    
  

    this.setStatus = function(uuid, status, onSuccess, onError) { 
        var data = { 
            status: status,
            uuid: uuid 
        };
        
        return arikaim.put('/api/users/admin/status',data,onSuccess,onError);           
    };

    this.init = function() {
        arikaim.ui.tab('.reports-tab-item','reports_content');
    };
}

var reportsAdmin = new ReportsAdmin();

$(document).ready(function() {
    reportsAdmin.init();
});