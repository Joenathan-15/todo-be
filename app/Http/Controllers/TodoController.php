<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Illuminate\Log\log;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::where("user_id", Auth::user()->id)->get();
        return response()->json($todos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vallidate = $request->validate([
            "content" => "required"
        ]);
        try {
            DB::beginTransaction();
            $todo = new Todo();
            $todo->content = $vallidate["content"];
            $todo->user_id = Auth::user()->id;
            $todo->save();
            DB::commit();
            return response()->json(["message" => "todo has been added"], 200);
        } catch (Exception $err) {
            log($err);
            DB::rollBack();
            return response()->json(["message" => "Server Error Try again later"], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $vallidate = $request->validate([
            "content" => "required"
        ]);
        try {
            DB::beginTransaction();
            $todo = Todo::find($id);
            $todo->content = $vallidate["content"];
            $todo->save();
            DB::commit();
            return response()->json(["message" => "todo has been updated"], 200);
        } catch (Exception $err) {
            log($err);
            DB::rollBack();
            return response()->json(["message" => "Server Error Try again later"], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try{
            DB::beginTransaction();
            Todo::destroy($id);
            DB::commit();
            return response()->json(["message" => "todo has been deleted"], 200);
        }catch (Exception $err) {
            log($err);
            DB::rollBack();
            return response()->json(["message" => "Server Error Try again later"], 500);
        }
    }
}
