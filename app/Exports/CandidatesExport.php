<?php

namespace App\Exports;

use App\Models\Candidate;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class CandidatesExport implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    public function __construct(private readonly ?Collection $candidates = null)
    {
    }

    public function view(): View
    {
        $candidates = $this->candidates ?? Candidate::with([
            'user.candidateSkill',
            'user.candidateLanguage',
            'industry',
            'maritalStatus',
            'careerLevel',
            'functionalArea',
        ])->get();

        return view('exports.candidates', ['candidates' => $candidates]);
    }

    public function title(): string
    {
        return 'Candidates';
    }

    /**
     * @return \Closure[]
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}
