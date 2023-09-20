<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Route::post('/adminlogin',[UserController::class,'adminLogin'];)
Route::post('/signup', [UserController::class, 'UserRegistration']);
Route::post('/login', [UserController::class, 'UserLogin']);
Route::post("/createcomment", [CommentController::class, "WriteComment"]);
Route::get("/getposts", [PostController::class, "getAllPosts"]);
Route::get("/getpostbyid/{id}", [PostController::class, "getPostById"]);
Route::get("/getcomments/{id}", [CommentController::class, "getCommentsByPostId"]);
Route::middleware(['auth:sanctum', 'isverified'])->group(function () {
    Route::post("/createpost", [PostController::class, "WritePost"]);
    Route::delete("/deletepost/{id}", [PostController::class, "Dell_Post"]);
    Route::patch("/updatepost/{id}", [PostController::class, "Edit_Update_Post"]);
    Route::delete("/deletecomment/{id}", [CommentController::class, "Dell_Comment"]);
});

Route::middleware(['auth:sanctum', "isAdmin"])->group(function () {
    Route::patch("/verifyuser/{id}", [UserController::class, "verifyUser"]);
});

