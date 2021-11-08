'use strict';

arikaim.component.onLoaded(function() { 
    $('#field_chart_dropdown').dropdown({
        onChange: function(value) {
            arikaim.page.loadContent({
                id: 'report_chart',
                component: 'reports::admin.reports.details.chart',
                params: { field_id: value }
            }); 
        }
    });
});