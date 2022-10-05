<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Information') }}</h3>
    </div>
    <div class="card-body">
        <dl>
            <dt>{{ __('Name') }}</dt>
            <dd>{{ $salesOrder->name }}</dd>
            <dt>{{ __('User') }}</dt>
            <dd>
                <div>{{ $salesOrder->user->name }}</div>
                <div>{{ $salesOrder->user->email }}</div>
            </dd>
        </dl>
    </div>
</div>
