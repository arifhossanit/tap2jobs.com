<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CompaniesExport implements FromView, WithTitle, ShouldAutoSize
{
    public function __construct(private readonly Collection $companies)
    {
    }

    public function view(): View
    {
        return view('exports.companies', [
            'companies' => $this->companies,
        ]);
    }

    public function title(): string
    {
        return 'Employers';
    }
}
