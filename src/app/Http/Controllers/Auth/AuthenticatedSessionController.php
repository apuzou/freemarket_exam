<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyAuthenticatedSessionController;

class AuthenticatedSessionController extends FortifyAuthenticatedSessionController
{
    public function store(Request $request)
    {
        $loginRequest = new LoginRequest();
        $validator = Validator::make(
            $request->all(),
            $loginRequest->rules(),
            $loginRequest->messages()
        );

        if ($validator->fails()) {
            throw (new ValidationException($validator))
                ->errorBag('default')
                ->redirectTo(route('login'));
        }

        return parent::store($request);
    }
}

