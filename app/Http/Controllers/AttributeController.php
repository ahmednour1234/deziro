<?php

namespace App\Http\Controllers;

use App\Repositories\AttributeRepository;
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
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->attributeRepository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

        $attributes = $this->attributeRepository->orderBy('id', 'DESC')->paginate(request()->get('limit') ?: default_limit());

        return view($this->_config['view'], compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        try {
            $this->validate(
                request(),
                [
                    'code'  => ['required', 'unique:attributes,code', new \App\Contracts\Validations\Code],
                    'name'  => 'required',
                    'type'  => 'required',
                    'options.*.name'  => 'required',
                ],
                [
                    'options.*.name.required'  => 'Name is required!',
                ]
            );

            $this->attributeRepository->create(array_merge(request()->all(), [
                'is_user_defined' => 1,
            ]));

            session()->flash('success', trans('Attribute created successfully.'));

            return redirect()->route($this->_config['redirect']);
        } catch (ValidationException $validationException) {

            session()->flash('error', $validationException->getMessage());

            return redirect()->back()->withInput(request()->input())->withErrors($validationException->errors());
        } catch (\Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return redirect()->back()->withInput(request()->input());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        return view($this->_config['view'], compact('attribute'));
    }

    /**
     * Get attribute options associated with attribute.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function getAttributeOptions($id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        return $attribute->options()->paginate(50);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            $this->validate(
                request(),
                [
                    'code'  => ['required', 'unique:attributes,code,' . $id, new \App\Contracts\Validations\Code],
                    'name'  => 'required',
                    'type'  => 'required',
                    'options.*.name'  => 'required',
                ],
                [
                    'options.*.name.required'  => 'Name is required!',
                ]
            );
            $this->attributeRepository->update(request()->all(), $id);

            session()->flash('success', trans('Attribute updated successfully.'));

            return redirect()->route($this->_config['redirect']);
        } catch (ValidationException $validationException) {

            session()->flash('error', $validationException->getMessage());

            return redirect()->back()->withInput(request()->input())->withErrors($validationException->errors());
        } catch (\Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return redirect()->back()->withInput(request()->input());
        }

        $this->attributeRepository->update(request()->all(), $id);

        session()->flash('success', trans('Attribute updated successfully.'));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        try {

            $this->attributeRepository->delete($id);
            $this->attributeRepository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
            $attributes = $this->attributeRepository->orderBy('id', 'DESC')->paginate(request()->get('limit') ?: default_limit());
            session()->flash('success', 'Attribute deleted successfully.');
            return response()->json([
                'success' => true,
                'message' => trans('Attribute deleted successfully.'),
                'html' => view('admin.attributes.attributes', ['attributes' => $attributes])->render()
            ]);
        } catch (\Exception $e) {
        }

        session()->flash('error', trans('Error encountered while deleting ' . $attribute->name));
        return response()->json([
            'success' => false,
            'message' => trans('Error encountered while deleting ' . $attribute->name)
        ]);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $index) {
                $attribute = $this->attributeRepository->find($index);

                if (!$attribute->is_user_defined) {
                    session()->flash('error', trans('admin::app.response.user-define-error'));

                    return redirect()->back();
                }
            }

            foreach ($indexes as $index) {

                $this->attributeRepository->delete($index);
            }

            session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'attributes']));
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));
        }

        return redirect()->back();
    }
}
