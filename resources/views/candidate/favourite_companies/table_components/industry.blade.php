@if(!empty($row->company->industry->name))
<div class="candidate-following-industry-badge">
    {{ $row->company->industry->name}}
</div>
@else
    <div class="candidate-following-industry-badge candidate-following-industry-badge--empty">
        {{ __('messages.n/a') }}
    </div>
@endif
