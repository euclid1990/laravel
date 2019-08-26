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
                'max' => 8192,
            ],
        ],
    ],
];
