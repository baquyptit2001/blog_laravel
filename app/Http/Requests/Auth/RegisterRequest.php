<?php

namespace App\Http\Requests\Auth;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:6|max:255|bail',
            'email' => 'required|string|email|max:255|unique:users|bail',
            'password' => 'required|string|min:8|confirmed|bail',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được bỏ trống',
            'name.min' => 'Tên phải có ít nhất 6 ký tự',
            'name.max' => 'Tên phải có tối đa 255 ký tự',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email chỉ được tối đa 255 ký tự',
            'email.unique' => 'Email đã được sử dụng',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function attributes()
    {
        return [
            'name' => 'Tên',
            'email' => 'Email',
            'password' => 'Mật khẩu',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @param array $errors
     * @return JsonResponse
     */

    public function response(array $errors): JsonResponse
    {
        return response()->json([
            'errors' => $errors->first(),
        ], 422);
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'errors' => $validator->errors()->first(),
                ],
                422
            )
        );
    }
}
