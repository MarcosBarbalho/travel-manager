<?php

namespace App\Http\Requests\TripOrder;

use App\Enums\TripOrder\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'departure_at' => [
                'sometimes',
                'date_format:Y-m-d H:i:s',
                'after_or_equal:now',
                Rule::when(isset($this->validationData()['return_at']), ['before:return_at'], []),
            ],
            'destination' => ['sometimes', 'string', 'max:128'],
            'requester_name' => ['sometimes', 'string', 'max:128'],
            'return_at' => [
                'sometimes',
                'nullable',
                'date_format:Y-m-d H:i:s',
                'after:departure_at',
            ],
            'status' => [
                'sometimes',
                'nullable',
                Rule::in([
                    Status::APPROVED->value,
                    Status::CANCELED->value,
                ]),
            ],
        ];
    }
}
