'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.component.loadLibrary('chart',function(result) {      
        var canvasId = $('.report-chart').attr('canvas-id');

        var chartOptions = {
            report_slug: $('.report-chart').attr('report-slug'),
            field_name: $('.report-chart').attr('field-name'),
            field_type: $('.report-chart').attr('field-type'),
            period: $('.report-chart').attr('period'),
            type: $('.report-chart').attr('type'),
            month: $('.report-chart').attr('month'),
            year: $('.report-chart').attr('year')
        };
    
        arikaim.put('/api/reports/chart',chartOptions,function(result) {                     
            var chart = new Chart($('#' + canvasId),result.chart);
        });
    });
});