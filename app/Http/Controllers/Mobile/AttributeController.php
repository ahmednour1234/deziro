<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Attribute;
use App\Repositories\AttributeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;

class AttributeController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(protected AttributeRepository $attributeRepository)
    {
        $this->middleware('auth:api', ['except' => []]);
        Auth::setDefaultDriver('api');
    }

    public function GetFilterableAttributes($category_id)
    {
        $user_id = Auth::user()->id;
        return response()->json([
            'data' => Attribute::collection($this->attributeRepository->getUserFilterAttributes($category_id, $user_id))
        ]);
    }
}
