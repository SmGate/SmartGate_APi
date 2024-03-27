<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BlockController extends Controller
{
    

    public function addblocks(Request $request)

    {

        $isValidate = Validator::make($request->all(), [
            'subadminid' => 'required|exists:users,id',
            'societyid' => 'required|exists:societies,id',
            'superadminid' => 'required|exists:users,id',
            'address' => 'required',
            'from' => 'required|integer|gt:0',
            'to' => 'required|integer|gt:from',
            'dynamicid' => 'required',
            'type' => 'required'
            
        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $blocks = new Block();

        $from = (int) $request->from;
        $to = (int) $request->to;



        for ($i = $from; $i < $to + 1; $i++) {


            $status = $blocks->insert(
                [

                    [
                        "address" =>  $request->address.$i,
                        'subadminid' => $request->subadminid,
                        'superadminid' => $request->superadminid,
                        'societyid' => $request->societyid,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'iteration' => $i,
                        'dynamicid' => $request->dynamicid,
                        'type' => $request->type
                        
                    ],

                ]
            );
        }

        return response()->json([
            "success" => true,
            "data" => $status,
        ]);
    }


    public function distinctblocks($subadminid)

    {

        $blocks = Street::where('bid', $subadminid)->join('blocks', 'blocks.id', '=', 'streets.id')
            ->join('phases', 'phases.id', '=', 'blocks.pid')->distinct()->get();
        $res = $blocks->unique('bid');

        return response()->json([
            "success" => true,
            "data" => $res->values()->all(),
        ]);
    }




    public function blocks($dynamicid,$type)

    {

        $blocks =  Block::where('dynamicid', $dynamicid)->where('type',$type)->get();


        return response()->json([
            "success" => true,
            "data" => $blocks,

        ]);
    }

    public function viewblocksforresidents($phaseid)
    {
        $phase = Block::where('pid', $phaseid)->get();
        return response()->json(["data" => $phase]);
    }
}
