/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ReportsListView() {
    var self = this;

    this.initRows = function() {

        $('.status-dropdown').dropdown({
            onChange: function(value) {               
                var uuid = $(this).attr('uuid');
                reportsAdmin.setStatus(uuid,value);
            }
        });   

        arikaim.ui.button('.view-report',function(element) {
            var uuid = $(element).attr('uuid');
          
            arikaim.page.loadContent({
                id: 'reports_content',
                component: 'reports::admin.reports.data',
                params: { uuid: uuid }
            }); 
        }); 

        arikaim.ui.button('.report-details',function(element) {
            var uuid = $(element).attr('uuid');
            arikaim.page.loadContent({
                id: 'report_details',
                component: 'reports::admin.reports.details',
                params: { uuid: uuid }
            });
        }); 

        arikaim.ui.button('.delete-report',function(element) {
            var uuid = $(element).attr('uuid');
            
            modal.confirmDelete({ 
                title: 'Confirm Delete',
                description: 'Delete report'
            },function() {
                reportsAdmin.deleteReport(uuid,function(result) {
                    arikaim.ui.table.removeRow('#row_' + result.uuid);
                });
            });
        }); 
    };
};

var reportsList = createObject(ReportsListView,ControlPanelView);

arikaim.component.onLoaded(function() {
    reportsList.initRows();  
}); 