<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        return [
            
                'name' => 'required',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'availability' => 'required|in:in_stock,out_of_stock',
                'featured' => 'nullable|boolean',
                 'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                 'category_id'=>'nullable' ,
            
        ];
    }
}
