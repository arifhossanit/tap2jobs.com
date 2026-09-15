import Sortable from 'sortablejs';
const initConsultationTypeSortable = () => {
    document.querySelectorAll('.consultation-type-sortable tbody').forEach((tbody) => {
        if (tbody.dataset.longPressSortable === 'true') return;
        tbody.dataset.longPressSortable = 'true';
        Sortable.create(tbody, {
            animation: 150, handle: '.consultation-type-drag-handle', delay: 100,
            delayOnTouchOnly: false, touchStartThreshold: 5,
            chosenClass: 'consultation-type-row-selected', ghostClass: 'consultation-type-row-placeholder',
            onEnd: () => {
                const ids = Array.from(tbody.querySelectorAll('tr[rowpk]')).map((row) => Number(row.getAttribute('rowpk'))).filter(Number.isInteger);
                const root = tbody.closest('[wire\\:id]');
                if (!root || !window.Livewire || ids.length === 0) return;
                window.Livewire.find(root.getAttribute('wire:id')).call('reorderConsultationTypes', ids);
            },
        });
    });
};
document.addEventListener('DOMContentLoaded', () => {
    initConsultationTypeSortable();
    new MutationObserver(initConsultationTypeSortable).observe(document.body, { childList: true, subtree: true });
});
