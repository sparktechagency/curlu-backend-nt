<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrivacyRequest;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        $privacy_policy = PrivacyPolicy::first();
        return response()->json($privacy_policy);
    }

    public function store(PrivacyRequest $request)
    {
        $privacy_policy = new PrivacyPolicy();
        $privacy_policy->title = $request->title;
        $privacy_policy->description = $request->description;
        $privacy_policy->save();
        return response()->json(['message' => 'Privacy and policy saved successfully','data'=>$privacy_policy]);
    }


    public function show(string $id)
    {
        $privacy_policy = PrivacyPolicy::find($id);

        if (!$privacy_policy) {
            return response()->json(['message' => 'Privacy and policy not found'], 404);
        }

        return response()->json($privacy_policy);
    }


    public function update(Request $request, string $id)
    {
        $privacy_policy = PrivacyPolicy::first();

        if (!$privacy_policy) {
            return response()->json(['message' => 'Privacy and policy not found'], 404);
        }

        $privacy_policy->title = $request->title;
        $privacy_policy->description = $request->description;
        $privacy_policy->save();
        return response()->json(['message' => 'Privacy and policy updated successfully']);
    }

    public function destroy(string $id)
    {
        $privacy_policy = PrivacyPolicy::find($id);

        if (!$privacy_policy) {
            return response()->json(['message' => 'Privacy and policy not found'], 404);
        }

        $privacy_policy->delete();

        return response()->json(['message' => 'Privacy and policy deleted successfully']);
    }
}
