<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationResolved;
use App\Models\Application;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


class ApplicationController extends Controller
{
    public static function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'sortby' => 'in:name,email,status,created_at,updated_at',
                'sortdir' => 'in:asc,desc',
                'status' => 'in:active,resolved'
            ]);
        } catch (ValidationException $exception) {
            return [
                'status' => 'error',
                'message' => $exception->getMessage()
            ];
        }
        $sortDirectory = $request->get('sortdir') != null ? $request->get('sortdir') : 'asc';
        $sortField = $request->get('sortby') != null ? $request->get('sortby') : 'id';
        if($request->get('status') != null) {
            return Application::getFilteredApps($request->get('status'), $sortField, $sortDirectory);
        }
        return Application::getApps($sortField, $sortDirectory);
    }

    public static function update(Request $request, int $id)
    {
        $app = Application::find($id);
        try {
            $validated = $request->validate([
                'comment' => 'required|max:255'
            ]);
        } catch (ValidationException $exception) {
            return [
                'status' => 'error',
                'message' => $exception->getMessage()
            ];
        }
        $app->status = 'Resolved';
        $app->comment = $request->comment;
        try {
            $app->save();
        } catch (QueryException $exception){
            return [
                'status' => 'error',
                'message' => $exception->getMessage()
            ];
        }
        try {
            Mail::to($app->email)->send(new ApplicationResolved($app));
        } catch (\Exception $exception) {
            return [
                'status' => 'error',
                'message' => $exception->getMessage()
            ];
        }

        return [
            'status' => 'successful'
        ];
    }

    public static function store(Request $request)
    {
        $app = new Application();
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email:rfc|max:255',
                'message' => 'required|max:255'
            ]);
        } catch (ValidationException $exception) {
            return [
                'status' => 'error',
                'message' => $exception->getMessage()
            ];
        }
        $app->name = $request->name;
        $app->email = $request->email;
        $app->message = $request->message;
        try {
            $app->save();
        } catch (QueryException $exception){
            return [
                'status' => 'error',
                'message' => $exception->getMessage()
            ];
        }
        return [
            'status' => 'successful',
            'id' => $app->id
        ];
    }
}
