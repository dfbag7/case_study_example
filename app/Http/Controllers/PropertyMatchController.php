<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PropertyMatchController extends Controller
{
    public function match($propertyId)
    {
        return 'hi, ' . $propertyId;
    }
}
