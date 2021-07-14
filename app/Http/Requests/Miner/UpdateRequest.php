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
            'miner_id' => [
                Rule::unique('miners', 'miner_id')
                    ->ignoreModel($this->route('miner'))
                    ->whereNull('deleted_at')
            ],
            'amount_paid' => [
                'required',
                'numeric',
            ],
            'purchase_date' => [
                'nullable',
                'date',
                'after_or_equal:2021-03-08'
            ],
            'estimated_activation_date' => [
                'nullable',
                'date',
                'after_or_equal:2021-03-13'
            ],
            'activation_date' => [
                'nullable',
                'date',
                'after_or_equal:2021-03-13'
            ],
        ];
    }

    public function type(): MinerType
    {
        return MinerType::whereSlug($this->input('type'))->first();
    }
}
