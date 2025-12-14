<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'login' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'last_name.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'last_name.string' => 'Поле "Фамилия" должно быть строкой.',
            'last_name.max' => 'Поле "Фамилия" не должно превышать 255 символов.',
            'first_name.required' => 'Поле "Имя" обязательно для заполнения.',
            'first_name.string' => 'Поле "Имя" должно быть строкой.',
            'first_name.max' => 'Поле "Имя" не должно превышать 255 символов.',
            'phone.required' => 'Поле "Телефон" обязательно для заполнения.',
            'phone.string' => 'Поле "Телефон" должно быть строкой.',
            'phone.max' => 'Поле "Телефон" не должно превышать 20 символов.',
            'phone.regex' => 'Телефон должен быть в формате +7 (XXX) XXX-XX-XX.',
            'phone.unique' => 'Пользователь с таким номером телефона уже зарегистрирован.',
            'login.required' => 'Поле "Логин" обязательно для заполнения.',
            'login.string' => 'Поле "Логин" должно быть строкой.',
            'login.max' => 'Поле "Логин" не должно превышать 255 символов.',
            'login.unique' => 'Пользователь с таким логином уже зарегистрирован.',
        ];
    }
}
