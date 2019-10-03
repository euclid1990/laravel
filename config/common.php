<?php

return [
    'item_per_page' => 12,
    'page_default' => 1,
    'import' => [
        'validation' => [
            'name' => [
                'max' => 25,
            ],
            'file' => [
                'header' => [
                    'id' => 'import.header_text.id',
                    'name' => 'import.header_text.name',
                    'created_at' => 'import.header_text.created_at',
                ],
                'type' => ['csv','tsv','xls','xlsx'],
                'max' => 4096,
            ],
        ],
    ],
    'export' => [
        'file_name' => 'Export-%s',
        'types' => [
            'xlsx' => [
                'label' => 'export.file_type.excel',
                'value' => 'xlsx',
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ],
            'csv' => [
                'label' => 'export.file_type.csv',
                'value' => 'csv',
                'mime' => 'text/csv',
                'separation' => [
                    'tab' => [
                        'label' => 'export.separate_char.tab',
                        'value' => '\t',
                    ],
                    'comma' => [
                        'label' => 'export.separate_char.comma',
                        'value' => ',',
                    ],
                    'semi_colon' => [
                        'label' => 'export.separate_char.semi_colon',
                        'value' => ';',
                    ],
                ],
            ],
        ],
        'export_column' => [
            'id' => 'export.export_column.id',
            'name' => 'export.export_column.name',
            'created_at' => 'export.export_column.created_at',
            'updated_at' => 'export.export_column.updated_at',
        ],
        'encoding' => [
            'utf8' => 'UTF-8',
            'shiftjis' => 'Shift-JIS',
            'eucjp' => 'EUC-JP',
        ],
    ],
];
