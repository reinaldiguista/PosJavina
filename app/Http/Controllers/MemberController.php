<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\CustomerType;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerType = CustomerType::all();
        
        return view('member.index', compact('customerType'));
    }

    public function data()
    {
        $member = Member::orderBy('name')->get();

        return datatables()
            ->of($member)
            ->addIndexColumn()
            ->addColumn('select_all', function ($member) {
                return '
                    <input type="checkbox" name="id[]" value="'. $member->id .'">
                ';
            })
            ->addColumn('name', function ($member) {
                return $member->name ;
            })
            ->addColumn('phone', function ($member) {
                return $member->phone ;
            })
            ->addColumn('customer_type', function ($member) {
                return $member->type->role ;
            })
            ->addColumn('aksi', function ($member) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('member.update', $member->id) .'`)" class="btn btn-xs btn-info "><i class="fa fa-pencil"></i>Edit Member</button>
                    <button type="button" onclick="deleteData(`'. route('member.destroy', $member->id) .'`)" class="btn btn-xs btn-danger "><i class="fa fa-trash"></i>Hapus Member</button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_member'])
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
        $member = Member::latest()->first() ?? new Member();

        $member = new Member();
        $member->name = $request->name;
        $member->phone = $request->phone;
        $member->customer_type = $request->customer_type;
        $member->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = Member::find($id);

        return response()->json($member);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $member = Member::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::find($id);
        $member->delete();

        return response(null, 204);
    }

    // public function cetakMember(Request $request)
    // {
    //     $datamember = collect(array());
    //     foreach ($request->id_member as $id) {
    //         $member = Member::find($id);
    //         $datamember[] = $member;
    //     }

    //     $datamember = $datamember->chunk(2);
    //     $setting    = Setting::first();

    //     $no  = 1;
    //     $pdf = PDF::loadView('member.cetak', compact('datamember', 'no', 'setting'));
    //     $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
    //     return $pdf->stream('member.pdf');
    // }

    public function cart_customer()
    {
        return view('member.index');
    }
}
