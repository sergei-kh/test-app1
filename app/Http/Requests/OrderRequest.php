<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * @var mixed
     */
    private $status;

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
     * @return array
     */
    public function rules()
    {
        return [
            'customer' => 'required|max:255',
            'phone' => 'required|max:255',
            'user_id' => 'required|integer',
            'type' => 'required|max:255',
            'status' => 'required|max:255',
        ];
    }
}
