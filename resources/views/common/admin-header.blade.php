@php
    $links = [
        ['route' => 'admin.users.index', 'label' => 'Users'],
        ['route' => 'plans.index', 'label' => 'Plans'],
        ['route' => 'admin.subscribers.index', 'label' => 'Subscriber'],
    ];
@endphp

<x-app-header home-route="admin.dashboard" home-label="Dashboard" :links="$links" />
