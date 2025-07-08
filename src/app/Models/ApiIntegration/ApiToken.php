<?php

namespace App\Models\ApiIntegration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiToken extends Model
{
    protected $fillable = [
        'token',
        'account_id',
        'api_service_id',
        'token_type_id',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

        public function ApiService(): BelongsTo
    {
        return $this->belongsTo(ApiService::class, 'api_service_id');
    }

    public function tokenType(): BelongsTo
    {
        return $this->belongsTo(TokenType::class, 'token_type_id');
    }
}
