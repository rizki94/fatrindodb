<?php

namespace App\Rules;

use Carbon\Carbon;
use App\Models\Profile;
use Illuminate\Contracts\Validation\Rule;

class MaxDateTransaction implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $maxDate = Profile::where('id', 'MAX_DATE_INPUT')->pluck('parameter')->first();
        $endDate = Carbon::now()->addDays((int)$maxDate);
        return $value <= $endDate;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $maxDate = Profile::where('id', 'MIN_DATE_INPUT')->pluck('parameter')->first();
        $endDate = Carbon::now()->addDays((int)$maxDate);
        return ':attribute must be less than ' . $endDate->toDateString();
    }
}
