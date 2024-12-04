<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function get_automatic_response(Request $request) {
        $data = $request->validate([
            'type' => ['required', 'string'],
            'id' => ['required', 'integer'],
        ]);

        if($data['type'] == 'category') {
            $Model = Category::find($data['id']);
        }

        if($data['type'] == 'subcategory') {
            $Model = Subcategory::find($data['id']);
        }

        if(empty($Model)) {
            return response()->json([
                'automatic_response' => null,
            ]);
        }

        return response()->json([
            'title' => $Model->name,
            'automatic_response' => $Model->automatic_response,
        ]);
    }
}
