<?php

namespace App\Http\Controllers;

use App\Models\InertiaTest;
use Illuminate\Http\Request;
use Inertia\Inertia;



class InertiaTestController extends Controller
{

    public function index()
    {
        return Inertia::render("Inertia/index", ['blogs' => InertiaTest::all()]);
    }

    public function create()
    {
        return Inertia::render("Inertia/create");
    }

    public function show($id)
    {
        return Inertia::render("Inertia/show",
                    [
                        'id' => $id,
                        'blog' => InertiaTest::findOrFail($id),
                        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required','max:20'],
            'content' => ['required'],
        ]);

        $inertiaTest = new InertiaTest;
        //dd($request);
        $inertiaTest->title = $request->get("title");
        $inertiaTest->content = $request->get("content");
        $inertiaTest->save();
        return to_route("inertia.index")->with([
            'message' => '登録しました',
            'status' => 'success'
        ]);
    }

    public function delete($id)
    {
        $blog = InertiaTest::findOrFail($id);
        $blog->delete();

        return to_route("inertia.index")->with([
            'message' => '削除しました',
            'status' => 'success'
        ]);
    }


}
