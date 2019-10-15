<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Remarkable
{
    /**
     * Remark Model remarks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function remarks(): MorphMany;
}
