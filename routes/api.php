<?php

use App\Http\Controllers\TodoController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

use function Illuminate\Log\log;

// Handle Authentication
Route::middleware("guest")->group(function () {
    // Handle Login Request
    Route::post("/login", function (Request $request) {
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required|min:8",
        ]);

        $user = User::where("email", $validated["email"])->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return response()->json(["token" => $user->createToken($user->id)->plainTextToken], 200);
    });

    // Handle Register Request
    Route::post("/register", function (Request $request) {
        $validated = $request->validate([
            "username" => "required|min:3",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8",
        ]);

        try {
            DB::beginTransaction();
            $user = new User();
            $user->username = $validated["username"];
            $user->email = $validated["email"];
            $user->password = bcrypt($validated["password"]);
            $user->save();
            DB::commit();
            return response()->json(["message" => "User has been created"], 200);
        } catch (Exception $err) {
            log($err);
            DB::rollBack();
            return response()->json(["message" => "Server Error Try again later"], 500);
        }
    });
});


// Handle Authenticated User Request
Route::middleware("auth:sanctum")->group(function () {

    // Get User Data
    Route::get('/user', function (Request $request) {
        return $request->user()->only("username", "email");
    });

    // Todo Interaction
    Route::prefix("/todo")->group(function () {
        Route::get('/', [TodoController::class, "index"]);
        Route::post('/', [TodoController::class, "store"]);
        Route::put('/{id}', [TodoController::class, "update"]);
        Route::delete('/{id}', [TodoController::class, "destroy"]);
    });
});
