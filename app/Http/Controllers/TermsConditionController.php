<?php

namespace App\Http\Controllers;

use App\Http\Requests\TermsRequest;
use App\Models\TermsCondition;
use Illuminate\Http\Request;

class TermsConditionController extends Controller
{

    public function index()
    {
        $terms_condition = TermsCondition::first();
        return response()->json($terms_condition);
    }

    public function store(TermsRequest $request)
    {
        $terms_condition = new TermsCondition();
        $terms_condition->title = $request->title;
        $terms_condition->description = $request->description;
        $terms_condition->save();
        return response()->json(['message' => 'Terms and condition saved successfully','data'=>$terms_condition]);
    }


    public function show(string $id)
    {
        $terms_condition = TermsCondition::find($id);

        if (!$terms_condition) {
            return response()->json(['message' => 'Terms and condition not found'], 404);
        }

        return response()->json($terms_condition);
    }


    public function update(Request $request, string $id)
    {
        $terms_condition = TermsCondition::first();

        if (!$terms_condition) {
            return response()->json(['message' => 'Terms and condition not found'], 404);
        }

        $terms_condition->title = $request->title;
        $terms_condition->description = $request->description;
        $terms_condition->save();
        return response()->json(['message' => 'Terms and condition updated successfully','data'=>$terms_condition]);
    }

    public function destroy(string $id)
    {
        $terms_condition = TermsCondition::find($id);

        if (!$terms_condition) {
            return response()->json(['message' => 'Terms and condition not found'], 404);
        }

        $terms_condition->delete();

        return response()->json(['message' => 'Terms and condition deleted successfully']);
    }
}
