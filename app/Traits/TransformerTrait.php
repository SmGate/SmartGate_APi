<?php
namespace App\Traits;

use League\Fractal\Resource\Collection;

trait TransformerTrait
{
    public static function transformLoginResponse($user)
    {
        $data= [
            'id'        => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'mobileno' => $user->mobileno,
            'rolename' => $user->rolename,
            'cnic' => $user->cnic,
            'address' => $user->address,
            'status' => $user->status,
            'fcmtoken' => $user->fcmtoken,
            'image' => $user->image,
            "roleid"=> $user->roleid,
        ];
        if(!empty($user->financeManager))
        {
            $data['financemanagerid']=$user->financeManager->financemanagerid;
            $data['status']=$user->financeManager->status;
            $data['subadminid']=$user->financeManager->subadminid;
            $data['societyid']=$user->financeManager->societyid;
        }
        if(!empty($user->subadmin))
        {
            $data['financemanagerid']=$user->subadmin->subadminid;
            $data['subadminid']=$user->subadmin->subadminid;
            $data['societyid']=$user->subadmin->societyid;
        }
        if(!empty($user->resident))
        {
            $data['resident']=$user->resident;
            $data['resident_house_address']=$user->resident ? $user->resident->residentHouseAddress : "null";
        }
        if(!empty($user->role))
        {
            $data['role']=$user->role;
        }
        if(!$user->familymember->isEmpty())
        {
            $data['familymembers']=$user->familymember;
        }
        if(!empty($user->society))
        {
            $data['society']=$user->society;
        }
        if(!empty($user->superadmin))
        {
            $data['superadmin']=$user->superadmin;
        }
        
        if(!empty($user->gatekeeper))
        {
            $data['gatekeeper'] =$user->gatekeeper->user;
        }

        return $data;

    }
    public static function transformSales($sales, $is_collection=true)
    {
        $data=[];
        $data['sales']=[];
        $total_amount_paid = 0;
        if($is_collection){
            foreach ($sales as $key => $sale) {
                $data['sales'][] = Self::transformSale($sale);
                $total_amount_paid += $sale->totalpaidamount;
            }
        } else {
            $data['sales'] = Self::transformSale($sales); // Transform a single sale
            $total_amount_paid += $sales->totalpaidamount;

        }
        $data['total_revenue']=$total_amount_paid;
        return $data;
    }
    public static function transformSale($sale)
{
    $data= [
        'id' => $sale->id,
        'description' => $sale->description,
        'charges' => $sale->charges,
        'latecharges' => $sale->latecharges,
        'appcharges' => $sale->appcharges,
        'tax' => $sale->tax,
        'balance' => $sale->balance,
        'payableamount' => $sale->payableamount,
        'totalpaidamount' => $sale->totalpaidamount,
        'residentid' => $sale->residentid,
        'subadminid' => $sale->subadminid,
        'financemanagerid' => $sale->financemanagerid,
        'billstartdate' => $sale->billstartdate,
        'billenddate' => $sale->billenddate,
        'duedate' => $sale->duedate,
        'month' => $sale->month,
        'billtype' => $sale->billtype,
        'specific_type' => $sale->specific_type,
        'paymenttype' => $sale->paymenttype,
        'status' => $sale->status,
    ];
    if($sale->resident){
        $data['resident'] = Self::transformResident($sale->resident); // Transform the resident
    }
    if($sale->resident && !empty($sale->resident->user)){
        $data['user'] = Self::transformUser($sale->resident->user); // Transform the user
    }
    if($sale->resident->residentHouseAddress && !empty($sale->resident->residentHouseAddress->society)){
        $data['society'] = Self::transformSociety($sale->resident->residentHouseAddress->society); // Transform the user
    }
    return $data;
}

    public static function transforSocieties($societies, $is_collection=true)
    {
        $data=[];
        $data['societies']=[];
        if($is_collection){
            foreach ($societies as $key => $society) {
                $data['societies'][] = Self::transformSociety($society);
            }
        } else {
            $data['societies'] = Self::transformSociety($societies); // Transform a single sale
        }
        return $data;
    }
    public static function tranformBills($bills, $is_collection=true)
    {
        $data=[];
        $data['bills']=[];
        if($is_collection){
            foreach ($bills as $key => $bill) {
                $data['bills'][] = Self::transformBill($bill);
            }
        } else {
            $data['bills'] = Self::transformBill($bills); // Transform a single sale
        }
        return $data;
    }
    public static function transformBill($bill)
    {
        $data= [
            'bill_id' => $bill->id,
            'subadminid' =>$bill->subadminid,
            'financemanagerid' =>$bill->financemanagerid,
            'propertyid' =>$bill->propertyid,
            'residentid' =>$bill->residentid,
            'status' => $bill->status,
            'paymenttype' => $bill->paymenttype,
            'is_previous' => $bill->is_previous,
            'specific_type' => $bill->specific_type,
            'billtype' => $bill->billtype,
            'paid_on' => $bill->paid_on,
            'month' => $bill->month,
            'duedate' => $bill->duedate,
            'billenddate' => $bill->billenddate,
            'billstartdate' => $bill->billstartdate,
            'totalpaidamount' => $bill->totalpaidamount,
            'payableamount' => $bill->payableamount,
            'balance' => $bill->balance,
            'tax' => $bill->tax,
            'appcharges' => $bill->appcharges,
            'latecharges' => $bill->latecharges,
            'charges' => $bill->charges,
            'description' => $bill->description,
        ];
        if($bill->subAdmin){
            $data['subadmin']=self::transformUser($bill->subAdmin->user);
        }
        if($bill->financeManager){
            $data['financemanager']=self::transformUser($bill->financeManager->user);
        }
        if($bill->user){
            $data['resident']=self::transformUser($bill->user);
        }
        if($bill->property){
            $data['property']=self::transformProperty($bill->property);
        }
        return $data;
    }
    public static function transformSociety($society)
    {
        $data= [
            'society_id' => $society->id,
            'subadmin_id' =>$society->subadmin->subadminid,
            'financemanager_id' =>$society->financemanager->financemanagerid,
            'name' => $society->name,
            'address' => $society->address,
            'country' => $society->country,
            'state' => $society->state,
            'city' => $society->city,
            'type' => $society->type,
            'area' => $society->area,
        ];
        if($society->subadmin){
            $data['subadmin']=self::transformUser($society->subadmin->user);
        }
        if($society->financemanager){
            $data['financemanager']=self::transformUser($society->financemanager->user);
        }
        return $data;
    }

public static function transformResident($resident)
{
    return [
        'resident_id' => $resident->residentid,
        'subadminid' => $resident->subadminid,
        'username' => $resident->username,
        'country' => $resident->country,
        'state' => $resident->state,
        'city' => $resident->city,
        'houseaddress' => $resident->houseaddress,
        'residenttype' => $resident->residenttype,
        'propertytype' => $resident->propertytype,
        'status' => $resident->status,
    ];
}
public static function transformProperty($property)
{
    return [
        'id'        => $property->id,
        'subadminid' => $property->subadminid,
        'occupied' => $property->occupied,
        'societyid' => $property->societyid,
        'dynamicid' => $property->dynamicid,
        'iteration' => $property->iteration,
        'type' => $property->type,
        'address' => $property->address,
    ];
}

public static function transformUser($user)
{
    return [
        'id'        => $user->id,
        'firstname' => $user->firstname,
        'lastname' => $user->lastname,
        'mobileno' => $user->mobileno,
        'rolename' => $user->rolename,
        'cnic' => $user->cnic,
        'address' => $user->address,
        'image' => $user->image,
    ];
}

    public static function successResponse($data=null, $message=null ,$code=null)
    {
        return response()->json([
            "message" => $message ?? "data retrieved successfully",
            "success" => true,
            "data" => $data ?? []
        ], $code ?? 200);
    }
    public static function errorResponse($errors=null, $message=null ,$code=null)
    {
        return response()->json([
            "errors" => $errors,
            "message" => $message ?? 'Something Went Wrong',
            "success" => false
        ], $code ?? 500);
    }
}
