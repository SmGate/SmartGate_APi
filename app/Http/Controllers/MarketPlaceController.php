<?php

namespace App\Http\Controllers;
use App\Models\Marketplaceimages;
use App\Models\Resident;
use Illuminate\Http\Request;
use App\Models\Marketplace;
use Illuminate\Support\Facades\Validator;


class MarketPlaceController extends Controller
{

    public function addProduct(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'residentid' => 'required|exists:residents,residentid',
            'societyid' => 'required|exists:societies,id',
            'subadminid' => 'required|exists:subadmins,subadminid',
            'productname' => 'required',
            'description' => 'required',
            'productprice' => 'required',
            'images' => 'required',
            'contact' => 'nullable',
            'category' => 'nullable',
            'condition' => 'nullable',
          


        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }


     


        $product = new Marketplace();
        $marketPlaceImages= new Marketplaceimages();


        $product->residentid = $request->residentid;
        $product->societyid = $request->societyid;
        $product->subadminid = $request->subadminid;
        $product->productname = $request->productname;
        $product->description = $request->description;
        $product->productprice = $request->productprice;
        $product->description = $request->description;
        $product->contact = $request->contact ?? "";
        $product->category = $request->category ?? "";
        $product->condition = $request->condition ?? "";
        $images = $request->file('images');
        $imageName = time() . "." . $images->extension();
        $images->move(public_path('/storage/'), $imageName);
        $product->save();

        $marketPlaceImages->images = $imageName;
        $marketPlaceImages->marketplaceid = $product->id;
        $marketPlaceImages->save();

      $product=  Marketplace::with('images')->find($product->id);
        
        return response()->json([
            'success' => true,
            'data' => $product,
            
        ]);
    }


    public function viewProducts($societyid,$category)
    {
       
        if($category=='All'){
        $products = Marketplace::where('societyid', $societyid)->with('users')
        ->with('residents')->with('images')->orderBy('created_at','desc')->get();
        }
        else {       
           

             $products = Marketplace::where('societyid', $societyid)->where('category', $category)->with('users')
            ->with('residents')->with('images')->orderBy('created_at','desc')->get();}
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function viewSellProductsResidnet($residentid)
    {
        $products = Marketplace::where('residentid', $residentid)
            ->with('users')
            ->with('residents')
            ->with('images')->orderBy('created_at','desc')->get();
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }




    public function productSellerInfo($residentid)
    {

        $chatneighbours =   Resident::where('residentid', $residentid)
            ->where('status', 1)->join('users', 'residents.residentid', '=', 'users.id')
            ->get();

        return
            response()->json([
                "success" => true,
                "data" => $chatneighbours
            ]);
    }



    public function productStatus(Request $request)

    {  $isValidate = Validator::make($request->all(), [

        'id' => 'required|exists:marketplaces,id',
        'status' => 'required|in:sold,unavailable,forsale',
    
      


    ]);


    if ($isValidate->fails()) {
        return response()->json([
            "errors" => $isValidate->errors()->all(),
            "success" => false

        ], 403);
    }


    $products = Marketplace::find($request->id);
    $products->status=$request->status;
    $products->update();

return response()->json([
    'success' => true,
    'data' => $products
    
]);




    }
}
