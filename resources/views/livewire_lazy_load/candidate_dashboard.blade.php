<div class="content flex-column-fluid">
    <div class="candidate-dashboard candidate-dashboard-skeleton" aria-hidden="true">
        <section class="candidate-dashboard-hero">
            <div class="candidate-dashboard-hero__profile">
                <div class="candidate-dashboard-skeleton__avatar shimmer-element"></div>
                <div class="candidate-dashboard-skeleton__identity">
                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--xs shimmer-element"></span>
                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--title shimmer-element"></span>
                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--wide shimmer-element"></span>
                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--medium shimmer-element"></span>
                    <div class="candidate-dashboard-skeleton__actions">
                        <span class="candidate-dashboard-skeleton__button shimmer-element"></span>
                        <span class="candidate-dashboard-skeleton__button shimmer-element"></span>
                    </div>
                </div>
            </div>

            <div class="candidate-profile-completion">
                <div class="candidate-dashboard-skeleton__ring shimmer-element"></div>
                <div class="candidate-profile-completion__content">
                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--small shimmer-element"></span>
                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--medium shimmer-element"></span>
                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--xs shimmer-element"></span>
                </div>
            </div>
        </section>

        <section class="candidate-dashboard-stats">
            @for($i = 0; $i < 4; $i++)
                <div class="candidate-stat-card">
                    <span class="candidate-dashboard-skeleton__stat-icon shimmer-element"></span>
                    <span class="candidate-stat-card__content">
                        <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--number shimmer-element"></span>
                        <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--small shimmer-element"></span>
                    </span>
                </div>
            @endfor
        </section>

        <section class="candidate-dashboard-main">
            <div class="candidate-dashboard-panel candidate-dashboard-panel--wide">
                <div class="candidate-dashboard-panel__header">
                    <div>
                        <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--heading shimmer-element"></span>
                        <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--wide shimmer-element"></span>
                    </div>
                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--small shimmer-element"></span>
                </div>

                <div class="candidate-match-grid">
                    @for($i = 0; $i < 4; $i++)
                        <article class="candidate-match-card">
                            <div class="candidate-match-card__top">
                                <span class="candidate-dashboard-skeleton__logo shimmer-element"></span>
                                <div class="candidate-match-heading">
                                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--medium shimmer-element"></span>
                                    <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--small shimmer-element"></span>
                                </div>
                            </div>
                            <div class="candidate-match-card__body">
                                <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--wide shimmer-element"></span>
                                <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--medium shimmer-element"></span>
                            </div>
                            <div class="candidate-match-card__footer">
                                <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--small shimmer-element"></span>
                                <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--xs shimmer-element"></span>
                            </div>
                        </article>
                    @endfor
                </div>
            </div>

            <div class="candidate-dashboard-side-stack">
                <aside class="candidate-dashboard-panel candidate-dashboard-side-panel">
                    <div class="candidate-dashboard-panel__header candidate-dashboard-panel__header--compact">
                        <div>
                            <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--heading shimmer-element"></span>
                        </div>
                    </div>
                    <div class="candidate-overview-list">
                        @for($i = 0; $i < 4; $i++)
                            <div>
                                <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--medium shimmer-element"></span>
                                <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--xs shimmer-element"></span>
                            </div>
                        @endfor
                    </div>
                </aside>

                <aside class="candidate-dashboard-panel candidate-dashboard-side-panel">
                    <div class="candidate-dashboard-panel__header candidate-dashboard-panel__header--compact">
                        <div>
                            <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--heading shimmer-element"></span>
                        </div>
                    </div>
                    <div class="candidate-profile-breakdown__list" style="display: grid; gap: 12px;">
                        @for($i = 0; $i < 5; $i++)
                            <div style="display: grid; gap: 6px;">
                                <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--small shimmer-element"></span>
                                <span class="candidate-dashboard-skeleton__line candidate-dashboard-skeleton__line--wide shimmer-element" style="height: 8px;"></span>
                            </div>
                        @endfor
                    </div>
                </aside>
            </div>
        </section>
    </div>
</div>
