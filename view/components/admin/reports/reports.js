/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ReportsAdmin() {
   
    this.add = function(formId, onSuccess, onError) {
        return arikaim.post('/api/admin/reports/add',formId,onSuccess,onError);         
    };

    this.update = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/reports/update',formId,onSuccess,onError);         
    };
    
    this.deleteReportData = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/reports/delete/data/' + uuid,onSuccess,onError);   
    };

    this.deleteReport = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/reports/delete/' + uuid,onSuccess,onError);   
    };

    this.setStatus = function(uuid, status, onSuccess, onError) { 
        var data = { 
            status: status,
            uuid: uuid 
        };
        
        return arikaim.put('/api/admin/reports/status',data,onSuccess,onError);           
    };    
}

var reportsAdmin = new ReportsAdmin();

arikaim.component.onLoaded(function() {
    arikaim.ui.tab('.reports-tab-item','reports_content');
});