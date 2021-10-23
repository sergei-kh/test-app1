<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Saving
{
    /**
     * The method saves data to the model
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $this->fill($request->all())->save();
    }
}
