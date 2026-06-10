@php
    $user = auth()->user();
    $links = [
        ['route' => 'user.plans', 'label' => 'Pricing'],
        ['route' => 'user.payment-history', 'label' => 'Payment History'],
        ['route' => 'projects.index', 'label' => 'Projects', 'feature' => 'project'],
        ['route' => 'team.index', 'label' => 'Team', 'feature' => 'team_management'],
        ['route' => 'analytics.index', 'label' => 'Analytics', 'feature' => 'analytics'],
    ];

    $links = array_map(function ($link) use ($user) {
        $link['disabled'] = isset($link['feature']) && ! $user->hasFeature($link['feature']);
        return $link;
    }, $links);
@endphp

<x-app-header home-route="user.dashboard" home-label="Dashboard" :links="$links" />
