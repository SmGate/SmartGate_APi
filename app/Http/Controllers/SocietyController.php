<?php

namespace App\Http\Controllers;

use App\Models\Society;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocietyController extends Controller
{

    public function addsociety(Request $request)

    {

        $isValidate = Validator::make($request->all(), [

            'country' => 'required',

            'state' => 'required',


            'city' => 'required',
            'area' => 'required',

            'type' => 'required',


            'name' => 'required',
            'address' => 'required',
            'superadminid' => 'required|exists:users,id',
            'structuretype' => 'required'



        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }


        $society = new Society();

        $society->country = $request->country;

        $society->state = $request->state;


        $society->city = $request->city;
        $society->area = $request->area;

        $society->type = $request->type;



        $society->name = $request->name;


        $society->address = $request->address;
        $society->superadminid = $request->superadminid;
        $society->structuretype = $request->structuretype;
        $society->save();


        return response()->json(["data" => $society]);
    }


    public  function updatesociety(Request $request)


    {
        $isValidate = Validator::make($request->all(), [

            // 'country' => 'required',

            // 'state' => 'required',


            // 'city' => 'required',
            // 'area' => 'required',

            // 'type' => 'required',


            'name' => 'required',

            'address' => 'required',
            // 'id' => 'required|exists:societies,id',
            'id' => 'required',


        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }


        $society = Society::find($request->id);

        // $society->country = $request->country;

        // $society->state = $request->state;



        // $society->city = $request->city;
        // $society->area = $request->area;

        // $society->type = $request->type;



        $society->name = $request->name;

        $society->address = $request->address;
        $society->update();


        return response()->json([
            "success" => true,

            "message" => "update successfully"
        ]);
    }

    public function viewallsocieties($superadminid)

    {


        $society = Society::where('superadminid', $superadminid)->get();


        return response()->json(["data" => $society]);
    }

    public function deletesociety($id)

    {


        $society = Society::where('id', $id)->delete();


        return response()->json(["data" => $society, "message" => "delete successfully"]);
    }



    public function viewsociety($societyid)
    {
        $society = Society::where('id', $societyid)->get();

        return response()->json(["data" => $society]);
    }


    public function viewsocietiesforresidents($type)
    {

        //   $society=  Society::where('type',$type)->get();

        $society = collect(Society::join('subadmins', function ($join) use ($type) {

            $join->on('societies.id', '=', 'subadmins.societyid')
                ->where('societies.type', '=', $type);
        })->select('societies.*', 'subadmins.subadminid')
            ->get());


        return response()->json(["data" => $society]);
    }


    public function    searchsociety($q)
    {


        $society = Society::where('name', 'LIKE', '%' . $q . '%')->orWhere('address', 'LIKE', '%' . $q . '%')->get();

        return response()->json(["data" => $society]);
    }
//  finance manager search route change

    // public function searchsociety($q, $superadminid = null)
    // {
    //     $society = Society::where(function ($query) use ($q) {
    //         $query->where('name', 'LIKE', '%' . $q . '%')
    //             ->orWhere('address', 'LIKE', '%' . $q . '%');
    //     })
    //         ->leftJoin('subadmins', 'societies.id', '=', 'subadmins.societyid')
    //         ->whereNotNull('subadmins.subadminid');

    //     if ($superadminid !== null) {
    //         $society->where('societies.superadminid', $superadminid);
    //     }

    //     $society = $society->get();

    //     if ($society->isEmpty()) {
    //         return response()->json(["message" => "No community found matching the search query or filters."]);
    //     }

    //     $result = [];

    //     foreach ($society as $societyData) {
    //         $result[] = [
    //             "societydata" => $societyData,
    //             "message" => "success."

    //         ];
    //     }

    //     return response()->json(["socitiesdata" => $result]);
    // }


    public function filtersocietybuilding($id, $type)
    {
        $society = Society::where('societies.superadminid', $id)
            ->where('societies.type', 'LIKE', '%' . $type . '%')
            ->leftJoin('subadmins', 'societies.id', '=', 'subadmins.societyid')
            ->whereNotNull('subadmins.subadminid')
            ->get();

        if ($society->isEmpty()) {
            return response()->json(["message" => "No community found."]);
        }

        $result = [];

        foreach ($society as $societyData) {
            $result[] = [
                "societydata" => $societyData,
                "message" => "Success."
            ];
        }

        return response()->json(["socitiesdata" => $result]);
    }




    public function allSocities($superadminid)
    {
        $society = Society::where('societies.superadminid', $superadminid)
            ->join('subadmins', 'societies.id', '=', 'subadmins.societyid')
            ->whereNotNull('subadmins.subadminid')
            ->get();

        if ($society->isEmpty()) {

            return response()->json(["message" => "No community found."]);
        }

        $result = [];

        foreach ($society as $societyData) {
            $result[] = [
                "societydata" => $societyData,
                "message" => "Success."
            ];
        }

        return response()->json(["socitiesdata" => $result]);
    }
}