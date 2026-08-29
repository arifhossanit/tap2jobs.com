<?php

namespace App\Livewire;

use App\Models\CompanySize;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CompanySizeTable extends LivewireTableComponent
{
    /**
     * @var string
     */
    protected $model = CompanySize::class;

    protected ?string $bulkDeleteModel = CompanySize::class;

    protected array $bulkDeleteBlockedChecks = [
        ['App\Models\Company', 'company_size_id'],
    ];

    /**
     * @var bool
     */
    public $showButtonOnHeader = true;
    public $showFilterOnHeader = false;

    /**
     * @var string
     */
    public $buttonComponent = 'company_sizes.table-components.add_button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('size', 'asc');

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('size')) {
                return[
                    'style' => 'width:50%',
                ];
            }

            return [
                'class' => 'text-center',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '3') {
                return [
                    'class' => 'text-center',
                    'width' => '15%',
                ];
            }

            return [];
        });

        $this->setQueryStringStatus(false);
    }

    public function builder(): Builder
    {
        return CompanySize::query()
            ->with('companyCategory')
            ->orderByRaw('CAST(size AS UNSIGNED) ASC');
    }

    public function placeholder()
    {
        return view('livewire_lazy_load/listing-skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.company_size.size'), 'size')
                ->searchable()
                ->sortable(function (Builder $query, string $direction) {
                    return $query->orderByRaw("CAST(size AS UNSIGNED) {$direction}");
                }),
            Column::make('Company Category', 'companyCategory.name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.created_date'), 'created_at')
                ->sortable()
                ->view('company_sizes.table-components.created_at'),
            Column::make(__('messages.common.action'), 'id')
                ->view('company_sizes.table-components.action_button'),
        ];
    }
}
