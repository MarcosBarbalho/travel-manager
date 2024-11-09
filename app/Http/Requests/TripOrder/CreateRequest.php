<?php

namespace App\Http\Requests\TripOrder;

use App\Enums\TripOrder\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'departure_at' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after_or_equal:now',
                Rule::when(isset($this->validationData()['return_at']), ['before:return_at'], []),
            ],
            'destination' => ['required', 'string', 'max:128'],
            'requester_name' => ['required', 'string', 'max:128'],
            'return_at' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
                'after:departure_at',
            ],
            'status' => ['nullable', Rule::in(Status::values())],
        ];
    }
}
