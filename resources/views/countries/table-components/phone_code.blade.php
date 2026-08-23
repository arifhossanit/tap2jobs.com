@if(!empty($row->phone_code))
    <div class="badge bg-light-info">
        {{ \Illuminate\Support\Str::startsWith($row->phone_code, '+') ? $row->phone_code : '+'.$row->phone_code }}
    </div>
@else
    <div class="badge bg-light-info">
        {{ __('messages.n/a') }}
    </div>
@endif
