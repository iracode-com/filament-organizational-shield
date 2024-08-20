<?php

return [
    'permission_prefixes' => [
        'resource' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',

            // custom
            'record_access',
            'self_records_access',
            'self_organizational_unit_records_access',
            'self_organizational_unit_and_subcategories_records_access',
            'same_role_records_access',
            'work_member_teams_access',
            'all_entities_access',
            'import_data',
            'export_to_excel'
        ],

        'page'   => 'page',
        'widget' => 'widget',

        'custom' => [
            'record_access',
            'self_records_access',
            'self_organizational_unit_records_access',
            'self_organizational_unit_and_subcategories_records_access',
            'same_role_records_access',
            'work_member_teams_access',
            'all_entities_access',
            'import_data',
            'export_to_excel'
        ],

        'default' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ]
    ]
];
