'use strict';

arikaim.component.onLoaded(function() { 
    $('#field_data_dropdown').dropdown({
        onChange: function(value) {
            arikaim.page.loadContent({
                id: 'field_data_rows',
                component: 'reports::admin.reports.details.data.rows',
                params: { field_id: value }
            }); 
        }
    });
});