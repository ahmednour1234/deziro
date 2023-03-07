<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Repositories\AttributeOptionRepository;
use App\Repositories\AttributeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;

class AttributeOptionController extends Controller
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
     * @param  \App\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @return void
     */
    public function __construct(protected AttributeOptionRepository $attributeOptionRepository)
    {
        $this->middleware('auth:api', ['except' => []]);
        auth()->setDefaultDriver('api');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        try {
            $this->validate(
                request(),
                [
                    'attribute_id'  => 'required|exists:attributes,id',
                    'options.*.name'  => 'required',
                    'options.*.position'  => 'nullable',
                ],
                [
                    'attribute_id.required'  => 'Attribute is required!',
                    'options.*.name.required'  => 'Name is required!',
                ]
            );

            $attribute = $this->attributeOptionRepository->createOrUpdate(request()->all());

            return response()->json([
                "success" => true,
                'message' => trans('Attributes created successfully.'),
                'data' => $attribute
            ]);
        } catch (ValidationException $validationException) {

            return response()->json([
                "success" => false,
                'message' => $validationException->getMessage(),
                'errors' => $validationException->errors()
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                "success" => false,
                'message' => $ex->getMessage(),
                'errors' => []
            ]);
        }
    }

    public function delete($id)
    {
        try {

            $this->attributeOptionRepository->findOrFail($id);
            $this->attributeOptionRepository->delete($id);

            return response()->json([
                "success" => true,
                'message' => 'Attribute deleted successfully.',
            ]);
        } catch (\Exception $e) {
        }

        return response()->json([
            "success" => false,
            'message' => 'Error! Attribute cannot be deleted',
        ]);
    }
}
