<?php

namespace App\Http\Requests\Api\V1\CloudAtCost;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PanelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    public function username(): string
    {
        return explode(':', base64_decode($this->get('credentials')))[0];
    }

    public function password(): string
    {
        return explode(':', base64_decode($this->get('credentials')))[1];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'credentials' => [
                'required',
            ]
        ];
    }

    /**
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if(
                !is_string($this->get('credentials')) ||
                count(explode(':', base64_decode($this->get('credentials')))) != 2
            )  {
                $validator->errors()->add('credentials', 'Please send your credentials base64-encoded separated by a colon (:)');
            }
        });
    }
}
