<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    public function index()
    {
        $faqs = Faq::paginate(4);
        return response()->json($faqs);
    }

    public function store(FaqRequest $request)
    {
        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        return response()->json(['message' => 'Faq Added Successfully','data'=>$faq],200);
    }

    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        $faq = Faq::find($id);
        if (empty($faq)) {
            return response()->json(['message', 'Faq Not Found'],404);
        }
        $faq->question = $request->question ?? $faq->question;
        $faq->answer = $request->answer ?? $faq->answer;
        $faq->save();
        return response()->json('message', 'Faq Updated Successfully');
    }

    public function destroy(string $id)
    {
        $faq = Faq::find($id);
        if (empty($faq)) {
            return response()->json(['message' => 'Faq Not Found'],404);
        }
        $faq->delete();
        return response()->json(['message' => 'Faq Deleted Successfully']);
    }
}
