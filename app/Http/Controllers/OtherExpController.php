<?php

namespace App\Http\Controllers;

use App\Models\Other_Exp;
use Illuminate\Http\Request;

class OtherExpController extends Controller
{
    public function add_form()
    {
        return view('client.other_exp.add');
    }
    public function show()
    {
        $exps = Other_Exp::orderBy('id', 'desc')->paginate(10);
        return view('client.other_exp.list', compact('exps'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required'],
            'title' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
        ]);
        $exp = Other_Exp::create($request->all());

        return redirect()->route('exp.show');
    }

    public function update_form($id)
    {
        $exp = Other_Exp::findorFail($id);
        return view('client.other_exp.update', compact('exp'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => ['required'],
            'title' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
        ]);
        $exp = Other_Exp::findorFail($id);
        $exp->update($request->all());

        return redirect()->route('exp.show');
    }

    public function delete($id)
    {
        $exp = Other_Exp::findorFail($id);
        $exp->delete();
        return redirect()->route('exp.show');
    }

    public function search(Request $request)
    {
        $exps = Other_Exp::where('title', 'like', '%' . $request->search . '%')->paginate(10);
        return view('client.other_exp.list', compact('exps'));
    }
}
