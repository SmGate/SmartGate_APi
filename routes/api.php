<?php

use App\Http\Controllers\BlockedUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubAdminController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\GateKeeperController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\PreApproveEntryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\ChatRoomUserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\SocietyBuildingController;
use App\Http\Controllers\SocietyBuildingApartmentController;
use App\Http\Controllers\SocietyBuildingFloorController;
use App\Http\Controllers\LocalBuildingFloorController;
use App\Http\Controllers\LocalBuildingApartmentController;
use App\Http\Controllers\DiscussionRoomController;
use App\Http\Controllers\DiscussionChatController;
use App\Http\Controllers\VistorDetailController;
use App\Http\Controllers\MarketPlaceController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\FinanceManagerController;






Route::middleware(['auth:sanctum'])->group(function () {



  Route::post('society/addsociety', [SocietyController::class, 'addsociety']);
  Route::post('society/updatesociety', [SocietyController::class, 'updatesociety']);
  Route::get('society/viewallsocieties/{userid}', [SocietyController::class, 'viewallsocieties']);
  Route::get('society/deletesociety/{id}', [SocietyController::class, 'deletesociety']);
  Route::get('society/viewsociety/{societyid}', [SocietyController::class, 'viewsociety']);
  Route::get('society/searchsociety/{q?}', [SocietyController::class, 'searchsociety']);
  Route::get('society/filtersocietybuilding/{id}/{q?}', [SocietyController::class, 'filtersocietybuilding']);
  Route::get('society/viewsocietiesforresidents/{type?}', [SocietyController::class, 'viewsocietiesforresidents']);
  Route::get('society/allSocities/{superadminid}', [SocietyController::class, 'allSocities']);
  // Route::get('society/viewbuildingsforresidents', [SocietyController::class, 'viewbuildingsforresidents']);
  //User
  Route::post('logout', [RoleController::class, 'logout']);
  Route::post('fcmtokenrefresh', [RoleController::class, 'fcmtokenrefresh']);
  Route::post('resetpassword', [RoleController::class, 'resetpassword']);
  // SubAdmin
  Route::post('registersubadmin', [SubAdminController::class, 'registersubadmin']);
  Route::get('viewsubadmin/{id}', [SubAdminController::class, 'viewsubadmin']);
  Route::get('deletesubadmin/{id}', [SubAdminController::class, 'deletesubadmin']);
  Route::post('updatesubadmin', [SubAdminController::class, 'updatesubadmin']);
  // Residents
  Route::post('registerresident', [ResidentController::class, 'registerresident']);
  Route::post('updateusername', [ResidentController::class, 'updateUserName']); 
  Route::post('updatechatvisibility', [ResidentController::class, 'updateChatVisibility']); 
  Route::post('checkchatvisibility', [ResidentController::class, 'checkChatVisibility']); 
  Route::get('viewresidents/{id}', [ResidentController::class, 'viewresidents']);
  Route::get('deleteresident/{id}', [ResidentController::class, 'deleteresident']);
  Route::get('searchresident/{subadminid}/{q?}', [ResidentController::class, 'searchresident']);
  Route::post('updateresident', [ResidentController::class, 'updateresident']);
  Route::get('loginresidentdetails/{residentid}', [ResidentController::class, 'loginresidentdetails']);
  Route::get('unverifiedresident/{subadminid}/{status}', [ResidentController::class, 'unverifiedresident']);
  Route::get('unverifiedhouseresident/{subadminid}/{status}', [ResidentController::class, 'unverifiedhouseresident']);
  Route::get('unverifiedapartmentresident/{subadminid}/{status}', [ResidentController::class, 'unverifiedapartmentresident']);
  Route::post('loginresidentupdateaddress', [ResidentController::class, 'loginresidentupdateaddress']);
  Route::post('verifyresident', [ResidentController::class, 'verifyresident']);
  Route::post('verifyhouseresident', [ResidentController::class, 'verifyhouseresident']);
  Route::post('verifyapartmentresident', [ResidentController::class, 'verifyapartmentresident']);
  Route::get('filterResident/{subadminid}/{type}', [ResidentController::class, 'filterResident']);
  Route::get('unverifiedresidentcount/{subadminid}', [ResidentController::class, 'unverifiedresidentcount']);

  // GateKeeper
  Route::post('registergatekeeper', [GateKeeperController::class, 'registergatekeeper']);
  Route::get('viewgatekeepers/{id}', [GateKeeperController::class, 'viewgatekeepers']);
  Route::get('deletegatekeeper/{id}', [GateKeeperController::class, 'deletegatekeeper']);
  Route::post('updategatekeeper', [GateKeeperController::class, 'updategatekeeper']);


  //Events

  Route::post('event/addevent', [EventController::class, 'addevent']);
  Route::post('event/addeventimages', [EventController::class, 'addeventimages']);
  Route::post('event/updateevent', [EventController::class, 'updateevent']);
  Route::get('event/events/{userid}', [EventController::class, 'events']);
  Route::get('event/deleteevent/{id}', [EventController::class, 'deleteevent']);
  Route::get('event/searchevent/{userid}/{q?}', [EventController::class, 'searchevent']);



  //Notice Board
  Route::post('addnoticeboarddetail', [NoticeBoardController::class, 'addnoticeboarddetail']);
  Route::get('viewallnotices/{id}', [NoticeBoardController::class, 'viewallnotices']);
  Route::get('deletenotice/{id}', [NoticeBoardController::class, 'deletenotice']);
  Route::post('updatenotice', [NoticeBoardController::class, 'updatenotice']);


  // Reports
  Route::post('reporttoadmin', [ReportController::class, 'reporttoadmin']);
  Route::get('adminreports/{residentid}', [ReportController::class, 'adminreports']);
  Route::post('updatereportstatus', [ReportController::class, 'updatereportstatus']);
  Route::get('deletereport/{id}', [ReportController::class, 'deletereport']);
  Route::get('reportedresidents/{subadminid}', [ReportController::class, 'reportedresidents']);
  Route::get('reports/{subadminid}/{userid}', [ReportController::class, 'reports']);
  Route::get('pendingreports/{subadminid}', [ReportController::class, 'pendingreports']);
  Route::get('historyreportedresidents/{subadminid}', [ReportController::class, 'historyreportedresidents']);
  Route::get('historyreports/{subadminid}/{userid}', [ReportController::class, 'historyreports']);
  Route::get('pendingreportscount/{subadminid}', [ReportController::class, 'pendingreportscount']);
  Route::get('inprogressreportscount/{subadminid}', [ReportController::class, 'inprogressreportscount']);


  // Preapproveentry
  Route::get('getgatekeepers/{subadminid}', [PreApproveEntryController::class, 'getgatekeepers']);
  Route::get('getvisitorstypes', [PreApproveEntryController::class, 'getvisitorstypes']);
  Route::post('addvisitorstypes', [PreApproveEntryController::class, 'addvisitorstypes']);
  Route::post('addpreapproventry', [PreApproveEntryController::class, 'addpreapproventry']);
  Route::post('updatepreapproveentrystatus', [PreApproveEntryController::class, 'updatepreapproveentrystatus']);
  Route::post('updatepreapproveentrycheckoutstatus', [PreApproveEntryController::class, 'updatepreapproveentrycheckoutstatus']);
  Route::get('viewpreapproveentryreports/{userid}', [PreApproveEntryController::class, 'viewpreapproveentryreports']);
  Route::get('preapproveentryresidents/{userid}', [PreApproveEntryController::class, 'preapproveentryresidents']);
  Route::get('preapproventrynotifications/{userid}', [PreApproveEntryController::class, 'preapproventrynotifications']);
  Route::get('preapproveentries/{userid}', [PreApproveEntryController::class, 'preapproveentries']);
  Route::get('preapproveentryhistories/{userid}', [PreApproveEntryController::class, 'preapproveentryhistories']);
  Route::get('unapprovedpreapproveentrycount/{gatekeeperid}', [PreApproveEntryController::class, 'unapprovedpreapproveentrycount']);
  Route::post('searchpreapproventry', [PreApproveEntryController::class, 'searchpreapproventry']);





  // Phases
  Route::post('addphases', [PhaseController::class, 'addphases']);
  Route::get('phases/{societyid}', [PhaseController::class, 'phases']);
  Route::get('distinctphases/{subadminid}', [PhaseController::class, 'distinctphases']);
  Route::get('viewphasesforresidents/{societyid}', [PhaseController::class, 'viewphasesforresidents']);

  // Blocks
  Route::post('addblocks', [BlockController::class, 'addblocks']);
  Route::get('blocks/{dynamicid}/{type}', [BlockController::class, 'blocks']);
  Route::get('distinctblocks/{bid}', [BlockController::class, 'distinctblocks']);
  Route::get('viewblocksforresidents/{phaseid}', [BlockController::class, 'viewblocksforresidents']);


  Route::get('viewblocksforresidents/{phaseid}', [BlockController::class, 'viewblocksforresidents']);
  // Streets
  Route::post('addstreets', [StreetController::class, 'addstreets']);
  Route::get('streets/{dynamicid}/{type}', [StreetController::class, 'streets']);
  Route::get('viewstreetsforresidents/{dynamicid}', [StreetController::class, 'viewstreetsforresidents']);

  // Property
  Route::post('addproperties', [PropertyController::class, 'addproperties']);
  Route::get('properties/{dynamicid}/{type}', [PropertyController::class, 'properties']);
  Route::get('viewpropertiesforresidents/{dynamicid}/{type}', [PropertyController::class, 'viewpropertiesforresidents']);



  // Society Building

  Route::post('addsocietybuilding', [SocietyBuildingController::class, 'addsocietybuilding']);
  Route::get('societybuildings/{dynamicid}/{type}', [SocietyBuildingController::class, 'societybuildings']);
  Route::get('allsocietybuildings/{subadminid}', [SocietyBuildingController::class, 'allsocietybuildings']);


  Route::post('addsocietybuildingfloors', [SocietyBuildingFloorController::class, 'addsocietybuildingfloors']);
  Route::get('viewsocietybuildingfloors/{buildingid}', [SocietyBuildingFloorController::class, 'viewsocietybuildingfloors']);
  Route::get('societybuildingfloor/{subadminid}', [SocietyBuildingFloorController::class, 'societybuildingfloor']);



  Route::post('addsocietybuildingapartments', [SocietyBuildingApartmentController::class, 'addsocietybuildingapartments']);
  Route::get('viewsocietybuildingapartments/{buildingid}', [SocietyBuildingApartmentController::class, 'viewsocietybuildingapartments']);




  // Family Members

  Route::post('addfamilymember', [FamilyMemberController::class, 'addfamilymember']);
  Route::get('viewfamilymember/{subadminid}/{residentid}', [FamilyMemberController::class, 'viewfamilymember']);



  //Chatroom
  Route::post('createchatroom', [ChatRoomController::class, 'createchatroom']);
  Route::post('chatroom/status', [ChatRoomController::class, 'chatRequestStatus']);
  Route::post('chatroom/status/chat-request', [ChatRoomController::class, 'sendChatRequestStatus']);



  Route::get('fetchchatroomusers/{userid}/{chatuserid}', [ChatRoomUserController::class, 'fetchchatroomusers']);


  //Chats
  Route::post('conversations', [ChatController::class, 'conversations']);
  Route::get('chatneighbours/{subadminid}', [ChatController::class, 'chatneighbours']);
  Route::get('chatgatekeepers/{subadminid}', [ChatController::class, 'chatgatekeepers']);
  Route::get('viewconversationsneighbours/{chatroomid}', [ChatController::class, 'viewconversationsneighbours']);


  // Measurements

  Route::post('addmeasurement', [MeasurementController::class, 'addmeasurement']);
  Route::get('housesapartmentmeasurements/{subadminid}/{type}', [MeasurementController::class, 'housesapartmentmeasurements']);

  //Bills
  Route::post('generatehousebill', [BillController::class, 'generatehousebill']);
  Route::post('generatesocietyapartmentbill', [BillController::class, 'generatesocietyapartmentbill']);
  Route::post('monthlybillupdateoverduedatestatus', [BillController::class, 'monthlybillupdateoverduedatestatus']);

  Route::get('generatedhousebill/{subadminid}', [BillController::class, 'generatedhousebill']);
  Route::get('generatedsocietyapartmentbill/{subadminid}', [BillController::class, 'generatedsocietyapartmentbill']);
  Route::get('monthlybills/{residenid}', [BillController::class, 'monthlybills']);
  Route::post('paybill', [BillController::class, 'paybill']);



  Route::post('verifyhouseresident', [ResidentController::class, 'verifyhouseresident']);


  //LOCAL BUILDING Floors
  Route::post('addlocalbuildingfloors', [LocalBuildingFloorController::class, 'addlocalbuildingfloors']);
  Route::get('viewlocalbuildingfloors/{buildingid}', [LocalBuildingFloorController::class, 'viewlocalbuildingfloors']);
  // LOCAL BUILDING APARTMENTS

  Route::post('addlocalbuildingapartments', [LocalBuildingApartmentController::class, 'addlocalbuildingapartments']);
  Route::get('viewlocalbuildingapartments/{localbuildingfloorid}', [LocalBuildingApartmentController::class, 'viewlocalbuildingapartments']);
  Route::post('verifylocalbuildingapartmentresident', [ResidentController::class, 'verifylocalbuildingapartmentresident']);
  Route::get('unverifiedlocalbuildingapartmentresident/{subadminid}/{status}', [ResidentController::class, 'unverifiedlocalbuildingapartmentresident']);

  //Discussion Forum
  Route::post('creatediscussionroom', [DiscussionRoomController::class, 'creatediscussionroom']);
  Route::post('discussionchats', [DiscussionChatController::class, 'discussionchats']);
  Route::get('alldiscussionchats/{discussionroomid}', [DiscussionChatController::class, 'alldiscussionchats']);


  //VISTOR DETAIL

  Route::post('addvistordetail', [VistorDetailController::class, 'addvistordetail']);
  Route::get('viewvistordetail/{societyid}', [VistorDetailController::class, 'viewvistordetail']);
  Route::get('searchResident/{subadminid}', [VistorDetailController::class, 'searchResident']);
  Route::post('updateVistorStatus', [VistorDetailController::class, 'updateVistorStatus']);
  //Market Place

  Route::post('addProduct', [MarketPlaceController::class, 'addProduct']);
  Route::post('product-status', [MarketPlaceController::class, 'productStatus']);
  Route::get('viewProducts/{societyid}/{category}', [MarketPlaceController::class, 'viewProducts']);
  Route::get('product-seller-info/{residentid}', [MarketPlaceController::class, 'productSellerInfo']);
  Route::get('viewSellProductsResidnet/{residentid}', [MarketPlaceController::class, 'viewSellProductsResidnet']);

  //Emergency
  Route::post('addEmergency', [EmergencyController::class, 'addEmergency']);
  Route::get('viewEmergency/{subadminid}', [EmergencyController::class, 'viewEmergency']);
  Route::get('emergencycount/{subadminid}', [EmergencyController::class, 'emergencycount']);


  // Finance Managers


  Route::post('finance-manager/register', [FinanceManagerController::class, 'register']);
  Route::get('finance-manager/view/{id}', [FinanceManagerController::class, 'view']);
  Route::get('finance-manager/delete/{id}', [FinanceManagerController::class, 'delete']);
  Route::post('finance-manager/update', [FinanceManagerController::class, 'update']);
  Route::get('finance-manager/bills/current-month-bills/{subadminid}/{financemanagerid}', [FinanceManagerController::class, 'currentMonthBills']);
  Route::get('finance-manager/bills/filter-bills/', [FinanceManagerController::class, 'filterBills']);
  Route::post('finance-manager/bills/search', [FinanceManagerController::class, 'billSearch']);

// Blocked User


  Route::post('blockuser', [BlockedUserController::class, 'blockuser']);
  Route::post('unblockuser', [BlockedUserController::class, 'unblockuser']);
  Route::post('checkblockuser', [BlockedUserController::class, 'checkblockuser']);


//INDIVIDUAL BILL

// Route::post('individual-bill/createIndividualBill', [IndividualBillController::class, 'createIndividualBill']);

// Route::get('individual-bill/getIndividualBillsForFinance/{subadminid}', [IndividualBillController::class, 'getIndividualBillsForFinance']);
// Route::get('individual-bill/getIndividualBillsByResident/{residentid}', [IndividualBillController::class, 'getIndividualBillsByResident']);
// Route::get('individual-bill/filterIndividualBills/', [IndividualBillController::class, 'filterIndividualBills']);
// Route::get('individual-bill/filterIndividualBillsByResident/', [IndividualBillController::class, 'filterIndividualBillsByResident']);

//Super Admin Finance Managers

// Route::post('superadmin-finance-manager/superAdminFinanceMangerRegister', [SuperAdminFinanceManagerController::class, 'superAdminFinanceMangerRegister']);
// Route::get('finance-manager/view/{id}', [SuperAdminFinanceManagerController::class, 'view']);
// Route::post('finance-manager/update', [SuperAdminFinanceManagerController::class, 'update']);
// Route::get('finance-manager/allresidentsBill/{residentid}', [SuperAdminFinanceManagerController::class, 'allresidentsBill']);
// Route::get('finance-manager/searchResidentsBill/{residentid}/{q?}', [SuperAdminFinanceManagerController::class, 'searchResidentsBill']);
// Route::get('super-finance-manager/filterBills/', [SuperAdminFinanceManagerController::class, 'filterBills']);
// Route::get('super-finance-manager/currentMonthBills/{residentid}', [SuperAdminFinanceManagerController::class, 'currentMonthBills']);

  

  

});




// Authentications

Route::post('login', [RoleController::class, 'login']);
Route::post('residentlogin', [ResidentController::class, 'residentlogin']);
Route::post('registeruser', [RoleController::class, 'registeruser']);
Route::post('forgetpassword', [RoleController::class, 'forgetpassword']);
Route::get('clear-cache', function() {
  $exitCode = Artisan::call('cache:clear');
  // return what you want
});
// for Resident and Family Member
Route::post('login/mobilenumber', [RoleController::class, 'loginWithMobileNumber']);
