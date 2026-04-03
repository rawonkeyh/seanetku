<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated admin can create vouchers
        return auth('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'package_id' => 'required|integer|exists:packages,id',
            'username' => 'required|string|max:255|unique:vouchers,username',
            'password' => 'required|string|max:255',
            'status' => 'nullable|in:available,reserved,sold',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'package_id.required' => 'Package ID wajib diisi',
            'package_id.exists' => 'Package tidak ditemukan',
            'username.unique' => 'Username voucher sudah digunakan',
            'username.required' => 'Username voucher wajib diisi',
            'password.required' => 'Password voucher wajib diisi',
        ];
    }
}
