<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class RegisterRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:6',
            'role' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter name',
            'email.required' => 'Please enter email address',
            'email.email' => 'Please enter valid email address',
            'email.unique' => 'Email address has already been taken',
            'password.required' => 'Please enter password',
            'password.min' => 'Password should be minimum :min characters long',
            'confirm_password.required' => 'Please confirm password',
            'confirm_password.same' => 'Password doesn\'t match',
            'role.required' => 'Please enter role',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json( ['success' => 0,'errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}