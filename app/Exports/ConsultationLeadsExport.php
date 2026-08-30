<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ConsultationLeadsExport implements FromView, WithTitle, ShouldAutoSize
{
    public function __construct(private readonly Collection $leads, private readonly ?string $leadSource)
    {
    }

    public function view(): View
    {
        return view('exports.consultation_leads', [
            'leads' => $this->leads,
            'leadSource' => $this->leadSource,
        ]);
    }

    public function title(): string
    {
        return 'Consultation Leads';
    }
}
