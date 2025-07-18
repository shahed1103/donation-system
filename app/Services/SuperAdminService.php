<?php

namespace App\Services;

use App\Models\Association;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;

class SuperAdminService
{

    public function countAssociations(): array
    {
        try {
            $count = Association::count();
            return [
                'count' => $count,
                'message' => 'done'
            ];
        } catch (Throwable $th) {

            throw $th;
        }
    }

}
