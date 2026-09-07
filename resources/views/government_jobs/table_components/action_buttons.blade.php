<div class="d-flex justify-content-center">
    <a href="{{ route('front.government-jobs.show', $row) }}" target="_blank"
       title="{{ __('messages.common.view') }}" class="btn px-2 text-info fs-3" data-bs-toggle="tooltip">
        <i class="fa-solid fa-eye"></i>
    </a>
    <a href="{{ route('admin.government-jobs.edit', $row) }}"
       title="{{ __('messages.common.edit') }}" class="btn px-2 text-primary fs-3" data-bs-toggle="tooltip">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <form method="POST" action="{{ route('admin.government-jobs.destroy', $row) }}"
          onsubmit="return confirm('Delete this government job?')">
        @csrf
        @method('DELETE')
        <button type="submit" title="{{ __('messages.common.delete') }}"
                class="btn px-2 text-danger fs-3" data-bs-toggle="tooltip">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>
