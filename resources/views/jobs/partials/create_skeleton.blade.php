<style>
    .job-create-page .job-create-skeleton {
        display: none;
        border: 1px solid #edf0f4;
        box-shadow: none;
    }

    .job-create-page.is-loading .job-create-skeleton {
        display: block;
    }

    .job-create-page.is-loading .job-create-form-card {
        display: none;
    }

    .job-create-skeleton__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px 22px;
    }

    .job-create-skeleton__field,
    .job-create-skeleton__editor,
    .job-create-skeleton__button {
        position: relative;
        overflow: hidden;
        background: #eef1f5;
        border-radius: 6px;
    }

    .job-create-skeleton__field::after,
    .job-create-skeleton__editor::after,
    .job-create-skeleton__button::after {
        content: "";
        position: absolute;
        inset: 0;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.72), transparent);
        animation: job-create-skeleton-shimmer 1.15s infinite;
    }

    .job-create-skeleton__label {
        width: 38%;
        height: 12px;
        margin-bottom: 10px;
        border-radius: 5px;
        background: #e2e7ee;
    }

    .job-create-skeleton__field {
        height: 44px;
    }

    .job-create-skeleton__wide {
        grid-column: 1 / -1;
    }

    .job-create-skeleton__editor {
        height: 132px;
    }

    .job-create-skeleton__actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 28px;
    }

    .job-create-skeleton__button {
        width: 108px;
        height: 42px;
    }

    @keyframes job-create-skeleton-shimmer {
        100% {
            transform: translateX(100%);
        }
    }

    @media (max-width: 767.98px) {
        .job-create-skeleton__grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card job-create-skeleton" aria-hidden="true">
    <div class="card-body">
        <div class="job-create-skeleton__grid">
            @for($i = 0; $i < 6; $i++)
                <div>
                    <div class="job-create-skeleton__label"></div>
                    <div class="job-create-skeleton__field"></div>
                </div>
            @endfor
            @for($i = 0; $i < 3; $i++)
                <div class="job-create-skeleton__wide">
                    <div class="job-create-skeleton__label"></div>
                    <div class="job-create-skeleton__editor"></div>
                </div>
            @endfor
            @for($i = 0; $i < 8; $i++)
                <div>
                    <div class="job-create-skeleton__label"></div>
                    <div class="job-create-skeleton__field"></div>
                </div>
            @endfor
        </div>
        <div class="job-create-skeleton__actions">
            <div class="job-create-skeleton__button"></div>
            <div class="job-create-skeleton__button"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.job-create-page.is-loading').forEach(function (page) {
            page.classList.remove('is-loading');
        });
    });
</script>
