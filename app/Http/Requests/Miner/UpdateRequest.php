<?php

namespace App\Http\Requests\Miner;

use App\Models\Miner\MinerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => [
                'required',
                Rule::exists('miner_types', 'slug')
            ],
            'identifier' => [
                'required',
                Rule::unique('miners', 'identifier')
                    ->ignoreModel($this->route('miner'))
            ],
            'amount_paid' => [
                'required',
                'digits_between:0,999999999',
            ]
        ];
    }

    public function type(): MinerType
    {
        return MinerType::whereSlug($this->input('type'))->first();
    }
}
