<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'atletas/buscar',
        // 'atletas/buscar-cpf',
        // 'atletas',
        // 'atletas/{id}',
        // 'atletas/create',
        // 'atletas/store',
        // 'atletas/update',
        // 'atletas/destroy'
    ];
}
