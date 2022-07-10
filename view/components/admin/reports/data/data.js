'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.ui.button('.delete-report-data',function(element) {
        var uuid = $(element).attr('uuid');
      
        modal.confirmDelete({ 
            title: 'Confirm Delete',
            description: 'Delete report data'
        },function() {
            reportsAdmin.deleteReportData(uuid,function(result) {
                arikaim.page.loadContent({
                    id: 'reports_content',
                    component: 'reports::admin.reports.data',
                    params: { uuid: uuid }
                });
            });
        });
    });
});