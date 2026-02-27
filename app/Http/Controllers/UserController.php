<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        // dd($users);
        return view('users.index', compact('users'));

    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function approve($id)
    {
    }

    public function reject($id)
    {
    }

    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:active,inactive'
        ]);
        
        try {
            $user = User::findOrFail($request->user_id);
            $user->status = $request->status;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
        
    }

    public function bulkApprove(Request $request)
    {
    }

    public function bulkDelete(Request $request)
    {
    }

    public function exportCsv()
    {
    }

    public function exportExcel()
    {
    }

    public function import(Request $request)
    {
    }

    public function search(Request $request)
    {
    }

    public function filterByStatus($status)
    {
       
        // স্ট্যাটাস অনুযায়ী ইউজার ফিল্টার করার লজিক
    }
}
