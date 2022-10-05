<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Attachments') }}</h3>
    </div>
    <div class="card-body">
        <ul class="list-unstyled">
            @foreach ($attachments as $attachment)
                <li>
                    <a
                        href="{{ $attachment->getUrl() }}"
                        target="_blank"
                    >{{ $attachment->file_name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
