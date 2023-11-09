<?php

namespace App\Rules;

use Carbon\Carbon;
use App\Models\Profile;
use Illuminate\Contracts\Validation\Rule;

class MinDateTransaction implements Rule
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
        $minDate = Profile::where('id', 'MIN_DATE_INPUT')->pluck('parameter')->first();
        $startDate = Carbon::now()->addDays((int)$minDate * -1);
        return $value >= $startDate;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $minDate = Profile::where('id', 'MIN_DATE_INPUT')->pluck('parameter')->first();
        $startDate = Carbon::now()->addDays((int)$minDate * -1);
        return ':attribute must be greather than ' . $startDate->toDateString();
    }
}
