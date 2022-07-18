<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Item;
use App\Models\Pajak;
use App\Models\Itempajak;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = Item::all();
        foreach ($item as $key => $value) {
            foreach ($value->pajak as $key2 => $value2) {
                $value->pajak[$key2] = $value2->pajakdetail;
                $value2->pajakdetail->rate = intval($value2->pajakdetail->rate).'%';
            }
        }
        return response()->json([
            "success" => true,
            "message" => "item List",
            "data" => $item
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
            "pajak_id" => "required|array|min:2"
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => "validation",
            ]);     
        }
        $item = Item::create(['nama' => $input['nama']]);
        foreach ($input['pajak_id'] as $key => $value) {
            $itempajak = Itempajak::create([
                'id_item' => $item->id,
                'id_pajak' => $value,
            ]);
        }
        return response()->json([
            "success" => true,
            "message" => "item created successfully.",
            "data" => $item
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
        $item = Item::find($id);
        foreach ($item->pajak as $key => $value) {
            $item->pajak[$key] = $value->pajakdetail;
            $value->pajakdetail->rate = intval($value->pajakdetail->rate).'%';
        }
        if (!$item) {
            return response()->json([
                "success" => false,
                "message" => "item not found.",
            ]);
        }
        return response()->json([
            "success" => true,
            "message" => "item retrieved successfully.",
            "data" => $item
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, item $item)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama' => 'required',
            "pajak_id" => "required|array|min:2"
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => "validation",
            ]); 
        }
        $item->update([
            'nama'     => $request->nama,
        ]);

        $itempajak = Itempajak::where('id_item',$item->id);
        $itempajak->delete();

        foreach ($input['pajak_id'] as $key => $value) {
            $itempajak = Itempajak::create([
                'id_item' => $item->id,
                'id_pajak' => $value,
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "item updated successfully.",
            "data" => $item
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        
        $itempajak = Itempajak::where('id_item',$item->id);
        $itempajak->delete();

        return response()->json([
            'message' => "item deleted successfully!",
        ], 200);
    }
}
