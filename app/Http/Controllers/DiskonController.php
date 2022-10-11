<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('diskon.index');
    }

    
    public function data()
    {
        $diskon = Diskon::get();

        // return response()->json('Data dikirim ke kasir', 400);
        
        return datatables()
        ->of($diskon)
        ->addIndexColumn()
            ->addColumn('select_all', function ($diskon) {
                return '
                    <input type="checkbox" name="id_diskon[]" value="'. $diskon->id .'">
                ';
            })
            ->addColumn('name', function ($diskon) {
                return '<span class="label label-success">'. $diskon->name .'</span>';
            })
            ->addColumn('discount', function ($diskon) {
                $x = $diskon->discount;
                if ($x > 100) {
                    return 'Rp.'. format_uang($x) . ',00';
                } else {
                    return $x . '%';
                }
            })
            ->addColumn('discount_limit', function ($diskon) {
                return $diskon->discount_limit ;
            })
            ->addColumn('count_limit', function ($diskon) {
                return $diskon->count_limit ;
            })
            ->addColumn('counter', function ($diskon) {
                return $diskon->count_limit ;
            })
            ->addColumn('status_discount', function ($diskon) {
                $x = "";
                $y = $diskon->status_discount;
                if ($y > 0) {
                    return "enable" ;
                } else {
                    return "disable" ;
                }
            })
            ->addColumn('aksi', function ($diskon) {
            return '
            <div class="btn-group">
                <button type="button" onclick="editForm(`'. route('diskon.update', $diskon->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-pencil"></i></button>
                <button type="button" onclick="deleteData(`'. route('diskon.destroy', $diskon->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i></button>
            </div>
            ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_diskon', 'name'])
            ->make(true);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $diskon = Diskon::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $diskon = Diskon::find($id);

        return response()->json($diskon);
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
    public function update(Request $request, $id)
    {
        
        $member = Diskon::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $diskon = Diskon::find($id);
        $diskon->delete();

        return response(null, 204);
    }
}
