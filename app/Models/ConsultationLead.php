<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationLead extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_QUALIFIED = 'qualified';
    public const STATUS_CONVERTED = 'converted';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_NEW => 'New',
        self::STATUS_CONTACTED => 'Contacted',
        self::STATUS_QUALIFIED => 'Qualified',
        self::STATUS_CONVERTED => 'Converted',
        self::STATUS_REJECTED => 'Rejected',
    ];

    public $table = 'consultation_leads';

    public $fillable = [
        'ad_id',
        'company_size_id',
        'company_category_id',
        'name',
        'email',
        'phone',
        'company_name',
        'designation',
        'company_website',
        'consultation_type',
        'preferred_contact_method',
        'preferred_contact_time',
        'message',
        'source_page',
        'clicked_url',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'id' => 'integer',
        'ad_id' => 'integer',
        'company_size_id' => 'integer',
        'company_category_id' => 'integer',
    ];

    protected $appends = ['status_label', 'consultation_type_label', 'preferred_contact_method_label'];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function companySize(): BelongsTo
    {
        return $this->belongsTo(CompanySize::class);
    }

    public function companyCategory(): BelongsTo
    {
        return $this->belongsTo(CompanyCategory::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getConsultationTypeLabelAttribute(): string
    {
        return ProfileReferenceOption::options(
            ProfileReferenceOption::TYPE_CONSULTATION_TYPE,
            [ProfileReferenceOption::SCOPE_EMPLOYER]
        )[$this->consultation_type] ?? ucfirst(str_replace('_', ' ', (string) $this->consultation_type));
    }

    public function getPreferredContactMethodLabelAttribute(): string
    {
        if (blank($this->preferred_contact_method)) {
            return '';
        }

        return ProfileReferenceOption::options(
            ProfileReferenceOption::TYPE_CONSULTATION_CONTACT_METHOD,
            [ProfileReferenceOption::SCOPE_EMPLOYER]
        )[$this->preferred_contact_method] ?? ucfirst(str_replace('_', ' ', (string) $this->preferred_contact_method));
    }
}
