<div class="d-flex align-items-center gap-2 py-1">
    <button type="button" class="btn btn-success bulk-import"
            data-url="{{ route('skills.import') }}"
            data-sample-file="{{ asset('sample-imports/skills-sample.csv') }}">
        Import
    </button>
    <a type="button" class="btn btn-primary addSkillModal">
        {{ __('messages.skill.add') }}
    </a>
</div>
