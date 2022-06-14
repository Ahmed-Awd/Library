<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default settings for charts
    |--------------------------------------------------------------------------
    */

    'default' => [
        'type'          => 'line',
        'library'       => 'google',
        'element_label' => 'Element',
        'title'         => 'My chart',
        'height'        => 400,
        'width'         => 500,
        'responsive'    => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets required by the libraries
    |--------------------------------------------------------------------------
    */

    'assets' => [
        'global' => [
            'scripts' => [
                'js/jquery.min.js',
            ],
        ],

        'canvas-gauges' => [
            'scripts' => [
                'js/new/gauge.min.js',
            ],
        ],

        'chartist' => [
            'scripts' => [
                'js/new/chartist.min.js',
            ],
            'styles' => [
                'css/new/chartist.min.css',
            ],
        ],

        'chartjs' => [
            'scripts' => [
                'js/new/Chart.min.js',
            ],
        ],

        'fusioncharts' => [
            'scripts' => [
                'js/new/fusioncharts.js',
                'js/new/fint.js',
            ],
        ],

        'google' => [
            'scripts' => [
                'js/new/jsapi.js',
                'js/new/loader.js',
                "google.charts.load('current', {'packages':['corechart', 'gauge', 'geochart', 'bar', 'line']})",
            ],
        ],

        'highcharts' => [
            'styles' => [
                'css/new/highcharts.css',
            ],
            'scripts' => [
                'js/new/highcharts.js',
                'js/new/exporting.js',
                'js/new/map.js',
                'js/new/data.js',
                'js/new/world.js',
            ],
        ],

        'justgage' => [
            'scripts' => [
                'js/new/raphael.min.js',
                'js/new/justgage.min.js',
            ],
        ],

        'morris' => [
            'styles' => [
                'css/new/morris.css',
            ],
            'scripts' => [
                'js/new/raphael.min.js',
                'js/new/morris.min.js',
            ],
        ],

        'plottablejs' => [
            'scripts' => [
                'js/new/d3.min.js',
                'js/new/plottable.min.js',
            ],
            'styles' => [
                'css/new/plottable.css',
            ],
        ],

        'progressbarjs' => [
            'scripts' => [
                'js/new/progressbar.min.js',
            ],
        ],

        'c3' => [
            'scripts' => [
                'js/new/d3.min.js',
                'js/new/c3.min.js',
            ],
            'styles' => [
                'css/new/c3.min.css',
            ],
        ],
    ],
];
