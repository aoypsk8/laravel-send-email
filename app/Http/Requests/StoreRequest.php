<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->user()->hasRole('super-admian|admin');
    }
    public function prepareForValidation()
    {
        if($this->isMethod('put') && $this->routeIs('edit.store') || $this->isMethod('delete') && $this->routeIs('delete.store')
        
        )
        $this->merge([
            'id' => $this->route()->parameters['id']
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->isMethod('delete') && $this->routeIs('delete.store')){
            return [
                'id' => [
                    'required',
                    'numeric',
                    Rule::exists('stores', 'id')->whereNull('deleted_at')
                ]
            ];
        }
        return [
            'name' => 'required|max:20' ,
            'email_contract' => 'required|email' ,
            'phone_number' => 'required|max:13' ,
            'address' => 'required' ,
            'logo' => 'required|mimes:jpg,png,jpeg|max:2048' ,
            'email'=> [
                'required',
                Rule::unique('users','email')
            ]
        ];

        if($this->isMethod('put') && $this->routeIs('edit.store')){
            return [
                'id' => [
                    'required',
                    'numeric',
                    Rule::exists('stores', 'id')->whereNull('deleted_at')
                ],
                 'name' => 'required|max:10',
                'email_contract' => 'required|email',
                'phone_number' => 'required|max:13',
                'address' => 'required',
                'logo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'must input your name',
        ];
    }
   
}
