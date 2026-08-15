<div class="d-flex align-items-center">
    <a>
        <div class="{{ checkLanguageSession() == 'ar' ? 'ms-3' : 'me-3' }}"
             style="width: 52px; height: 52px; flex: 0 0 52px; overflow: hidden; background: #f4f6f9;">
            <img src="{{$row->company->user->avatar}}" alt="user" class="user-img"
                 style="width: 100%; height: 100%; object-fit: contain;">
        </div>
    </a>
    <div class="d-flex flex-column">
            <a class="mb-1 text-decoration-none fs-6">
            {{$row->company->user->first_name}}
        </a>
        <span>{{$row->company->user->email}}</span>
    </div>
</div>
