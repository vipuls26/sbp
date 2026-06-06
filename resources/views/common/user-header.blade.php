@php
    $links = [
        ['route' => 'user.plans', 'label' => 'Pricing'],
    ];
@endphp

<x-app-header home-route="user.dashboard" home-label="Dashboard" :links="$links" />
