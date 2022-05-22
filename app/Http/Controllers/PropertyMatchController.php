<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyMatchController extends Controller
{
    public function match($propertyId)
    {
        $property = Property::find($propertyId);

        if ($property === null)
            \App::abort(404, 'No property found with given ID');

        return $property->getMatchedSearchProfiles();
    }
}
