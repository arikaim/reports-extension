'use strict';

arikaim.component.onLoaded(function() { 
    $('#reports_dropdown').dropdown({
        onChange: function(value) {
            arikaim.page.loadContent({
                id: 'report_data',
                component: 'reports::admin.reports.details.data',
                params: { report_id: value }
            }); 

            arikaim.page.loadContent({
                id: 'report_info',
                component: 'reports::admin.reports.details.info',
                params: { report_id: value }
            }); 

            arikaim.page.loadContent({
                id: 'report_chart',
                component: 'reports::admin.reports.chart',
                params: { report_id: value }
            }); 
        }
    });
});