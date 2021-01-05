<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/backend/pages/add/uploadImage',
        '/backend/pages/edit/*/editImage',

        '/backend/sliders/add/uploadImage',
        '/backend/sliders/edit/*/editImage',

        '/backend/panelSettings/editImage/*/',
        
        '/backend/photos/add/uploadImage',
        '/backend/photos/edit/*/editImage',

        '/backend/files/add/uploadImage',
        '/backend/files/edit/*/editImage',

        '/backend/users/add/uploadImage',
        '/backend/users/edit/*/editImage',

    ];
}
