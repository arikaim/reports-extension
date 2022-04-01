'use strict';

arikaim.component.onLoaded(function() {
    safeCall('reportsList',function(obj) {
        obj.initRows();
    },true);    
});