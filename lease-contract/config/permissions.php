<?php

return [
    [
        'name' => 'Lease contracts',
        'flag' => 'lease-contract.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'lease-contract.create',
        'parent_flag' => 'lease-contract.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'lease-contract.edit',
        'parent_flag' => 'lease-contract.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'lease-contract.destroy',
        'parent_flag' => 'lease-contract.index',
    ],

    [
        'name' => 'Lease contract details',
        'flag' => 'lease-contract-detail.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'lease-contract-detail.create',
        'parent_flag' => 'lease-contract-detail.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'lease-contract-detail.edit',
        'parent_flag' => 'lease-contract-detail.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'lease-contract-detail.destroy',
        'parent_flag' => 'lease-contract-detail.index',
    ],

    [
        'name' => 'Lease contract files',
        'flag' => 'lease-contract-file.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'lease-contract-file.create',
        'parent_flag' => 'lease-contract-file.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'lease-contract-file.edit',
        'parent_flag' => 'lease-contract-file.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'lease-contract-file.destroy',
        'parent_flag' => 'lease-contract-file.index',
    ],
];
