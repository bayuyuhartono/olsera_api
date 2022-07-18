<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Pajak;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pajak = Pajak::all();
        return response()->json([
            "success" => true,
            "message" => "Pajak List",
            "data" => $pajak
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama' => 'required',
            'rate' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => "validation",
            ]);     
        }
        $pajak = Pajak::create($input);
        return response()->json([
            "success" => true,
            "message" => "Pajak created successfully.",
            "data" => $pajak
        ]);
    } 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pajak = Pajak::find($id);
        if (!$pajak) {
            return response()->json([
                "success" => false,
                "message" => "pajak not found.",
            ]);
        }
        return response()->json([
            "success" => true,
            "message" => "pajak retrieved successfully.",
            "data" => $pajak
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pajak $pajak)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama' => 'required',
            'rate' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => "validation",
            ]); 
        }
        $pajak->update([
            'nama'     => $request->nama,
            'rate'   => $request->rate,
        ]);
        return response()->json([
            "success" => true,
            "message" => "pajak updated successfully.",
            "data" => $pajak
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pajak $pajak)
    {
        $pajak->delete();

        return response()->json([
            'message' => "pajak deleted successfully!",
        ], 200);
    }
}
