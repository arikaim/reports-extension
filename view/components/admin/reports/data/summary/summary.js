'use strict';

arikaim.component.onLoaded(function() { 
    var month = arikaim.ui.getComponent('month_filter').get('selected');
    var year = arikaim.ui.getComponent('year_filter').get('selected');
 
    $('#field_summary_dropdown').dropdown({
        onChange: function(value) {
            arikaim.page.loadContent({
                id: 'field_summary_content',
                component: 'reports::admin.reports.data.summary.field',
                params: { 
                    field_id: value,
                    year: year,
                    month: month
                }
            }); 
        }
    });

    arikaim.ui.subscribe('year_filter','selected',function(value) {
        var fieldId = $('#field_summary_dropdown').dropdown('get value');
        var month = arikaim.ui.getComponent('month_filter').get('selected');

        arikaim.page.loadContent({
            id: 'field_summary_content',
            component: 'reports::admin.reports.data.summary.field',
            params: { 
                field_id: fieldId,
                year: value,
                month: month
            }
        }); 
    });

    arikaim.ui.subscribe('month_filter','selected',function(value) {
        var fieldId = $('#field_summary_dropdown').dropdown('get value');
        var year = arikaim.ui.getComponent('year_filter').get('selected');

        arikaim.page.loadContent({
            id: 'field_summary_content',
            component: 'reports::admin.reports.data.summary.field',
            params: { 
                field_id: fieldId,
                year: year,
                month: value
            }
        }); 
    });
});