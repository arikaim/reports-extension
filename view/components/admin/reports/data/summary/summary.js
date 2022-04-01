'use strict';

arikaim.component.onLoaded(function() { 
    $('#field_summary_dropdown').dropdown({
        onChange: function(value) {
            arikaim.page.loadContent({
                id: 'field_summary_content',
                component: 'reports::admin.reports.data.summary.field',
                params: { field_id: value }
            }); 
        }
    });
});