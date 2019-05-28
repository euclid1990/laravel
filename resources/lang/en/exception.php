<?php

return [
    "default" => "Request have exception",
    "action" => "Action exception",
    "store" => "Not store database",
    "store_multiple" => "Not store multiple database",
    "update_multiple" => "Not update multiple database",
    "update" => "Not update database",
    "delete" => "Delete fail",
    "restore" => "Restore fail",
    "remove" => "Remove fail",
    "not_found" => "Item not exists",
    "not_owner" => "Not owner",
    "unknown_error" => "Unknown error",
    "data_invalid" => "Data invalid",
    // Role and permission exception
    'not_have_role' => 'User does not have the right roles. Necessary roles are ":roles"',
    'not_have_permission' => 'User does not have the right permissions. Necessary permissions are ":permissions"',
    'not_have_role_and_permission' => 'User does not have the right permissions. Necessary permissions are ":permissions"',
    'not_loggin' => 'User is not logged in.',
    'there_no_role_name' => 'There is no permission named ":roleName"',
    'there_no_role_id' => 'There is no permission with id ":roleId"',
    'there_no_permission_name' => 'There is no permission named ":permissionName"',
    'there_no_permission_id' => 'There is no permission with id ":permissionId"',
];
