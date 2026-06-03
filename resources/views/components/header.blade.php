<div>
    @if (auth()->user()->role->name === 'user')
        @include('common.user-header')
    @else
        @include('common.admin-header')
    @endif
</div>
