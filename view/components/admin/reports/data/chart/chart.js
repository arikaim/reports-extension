'use strict';

arikaim.component.onLoaded(function() { 
    $('#field_chart_dropdown').dropdown({
        onChange: function(value) {
            arikaim.page.loadContent({
                id: 'chart_content',
                component: 'reports::chart',
                params: { field_id: value }
            }); 
        }
    });
});