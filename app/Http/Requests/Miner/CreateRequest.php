<?php

namespace App\Http\Requests\Miner;

use App\Models\Miner\MinerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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
            'miner_id' => [
                'required',
                Rule::unique('miners', 'miner_id')
                    ->whereNull('deleted_at')
            ],
            'identifier' => [
                'required',
                Rule::unique('miners', 'identifier')
                    ->whereNull('deleted_at')
            ],
            'amount_paid' => [
                'required',
                'numeric',
            ]
        ];
    }

    public function type(): MinerType
    {
        return MinerType::whereSlug($this->input('type'))->first();
    }
}
