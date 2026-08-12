<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $now = Carbon::now('Asia/Amman');
        
        if ($now->hour < 21) {
            $earliestDate = $now->copy()->addDay()->format('Y-m-d');
        } else {
            $earliestDate = $now->copy()->addDays(2)->format('Y-m-d');
        }

        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(?:07[789]\d{7}|\+9627[789]\d{7})$/'],
            'location' => ['required', 'string', 'max:500'],
            'delivery_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . $earliestDate],
            'delivery_slot' => ['required', 'in:9-12,12-15,15-18,18-21'],
            'gift_note' => ['nullable', 'string', 'max:1000'],
            'special_instructions' => ['nullable', 'string', 'max:1000'],
            'website' => ['present', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $fail('Invalid request.');
                }
            }],
        ];
    }
}
