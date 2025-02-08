<?php

namespace App\Services;

use App\Models\Jurisprudence;
use App\Models\PhCase;

class JurisprudenceService
{
    public function search(string $query)
    {
        return PhCase::where('title', 'LIKE', "%{$query}%")
            ->orWhere('case_number', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->select('id', 'title', 'case_number', 'content')
            ->limit(10)
            ->get();
    }
}
