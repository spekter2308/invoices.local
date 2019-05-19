<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CompanyController extends Controller
{
    protected $company;

    const UPLOAD_PATH = 'upload/company';

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function index()
    {
        $company = $this->company->latest()->paginate(15);

        return view('company.index')->with([
            'company' => $company
        ]);
    }

    public function create()
    {
        if(\Gate::denies('create', Company::class)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t create company.']);
        }

        return view('company.create')->with([
            'company' => $this->company
        ]);
    }

    public function update($id)
    {
        $company = $this->company->find($id);

        return view('company.create')->with([
            'company' => $company
        ]);
    }

    public function createSave(Request $request)
    {
        $data = $request->all();

        $validator = $this->validateCompany($data);

        if ($validator->fails()) {
            $request->flash();
            return \redirect(route('company-create'))->withErrors($validator);
        }

        if (!empty($data['logo_img'])) {
            $data['logo_img'] = $data['logo_img']->hashName();
            $request->file('logo_img')->move(public_path(self::UPLOAD_PATH), $data['logo_img']);
        }

        if (!$this->company->create($data)) {
            abort(500);
        }

        return redirect(route('company-list'))->with(['success' => 'Company has been created']);

    }

    public function updateSave($id, Request $request)
    {
        $company = $this->company->findOrFail($id);
        if(\Gate::denies('update', $company)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t update company.']);
        }

        $data = $request->all();
        $validator = $this->validateCompany($data);

        if ($validator->fails()) {
            $request->flash();
            return \redirect(route('company-update', ['id' => $id]))->withErrors($validator);
        }

        if (!empty($data['logo_img'])) {
            $data['logo_img'] = $data['logo_img']->hashName();
            $request->file('logo_img')->move(public_path(self::UPLOAD_PATH), $data['logo_img']);
        }

        if (!$company->update($data)) {
            abort(500);
        }

        return redirect(route('company-update', ['id' => $id]))->with(['success' => 'Company has been updated']);

    }

    public function validateCompany($data, $flag = true)
    {
        $validator = \Validator::make($data, [
            'name' => 'required|min:3|max:100',
            'short_name' => 'nullable|min:3|max:50',
            'address' => 'required|min:3|max:100',
            'invoice_notes' => 'nullable|min:3|max:1000',
            'logo_img' => 'nullable|image|max:300',
        ]);

        return $validator;
    }

    public function deleteImage($id)
    {
        $company = $this->company->find($id);

        if (!File::delete(public_path(self::UPLOAD_PATH . '/' . $company->logo_img))) {
            abort(500);
        }

        $company->logo_img = '';

        $company->save();

        return redirect(route('company-update', ['id' => $id]))->with(['success' => 'Image has been delete']);

    }
}
