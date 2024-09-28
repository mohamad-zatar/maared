<?php

namespace App\Http\Controllers;

use App\Http\Traits\Responsable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use Responsable;

    public function createApiPaginator($data): array
    {
        return [
            'total_count' => $data->total(),
            'limit' => $data->perPage(),
            'total_page' => ceil($data->total() / $data->perPage()),
            'current_page' => $data->currentPage(),
        ];
    }
}
