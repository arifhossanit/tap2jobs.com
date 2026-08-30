{{ $row->company_category_name ?: $row->companyCategory?->name ?: $row->companySize?->companyCategory?->name ?: 'N/A' }}
