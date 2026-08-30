{{ $row->companyCategory?->name ?: $row->companySize?->companyCategory?->name ?: '-' }}
