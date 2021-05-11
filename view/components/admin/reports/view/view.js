/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function ReportsListView() {
    var self = this;
   
    this.init = function() {       
    };

    this.initRows = function() {
        arikaim.ui.button('.view-report',function(element) {
            var uuid = $(element).attr('uuid');
           
            
            arikaim.ui.setActiveTab('#view_report_tab','.reports-tab-item');
        }); 
    };
};

var reportsList = createObject(ReportsListView,ControlPanelView);

arikaim.component.onLoaded(function() {
    reportsList.init();
    reportsList.initRows();  
}); 