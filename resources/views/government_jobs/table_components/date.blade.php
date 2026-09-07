{{ $value ? \Carbon\Carbon::parse($value)->format('d M Y') : 'N/A' }}
