<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('post') ) {
            return [
                'name' => 'required|string|max:258',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', //uslovi za sliku
                'description' => 'required|string'
            ];
        } else {
            return [
                'name' => 'required|string|max:258',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', //uslovi za sliku
                'description' => 'required|string'
            ];
        }
    }

     /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        if(request()->isMethod('post') ) {
            return [
                'name.required' => 'Name is required!',
                'image.required' => 'Image is required!',
                'description.required' => 'Descritpion is required!'
            ];
        } else {
            return [
                'name.required' => 'Name is required!',
                'description.required' => 'Descritpion is required!'
            ];   
        }
    }
}
