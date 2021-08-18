<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\DesignCard;
use App\Models\CustomerMaster;
use App\Models\LoomMaster;
use App\Models\Staf_master;
use App\Models\WarpMaster;
use App\Models\FinishingMachineMaster;
use App\Models\WovenQuality;
use App\Http\Requests\Admin\WovenRequest;

class WovenController extends Controller
{
    public function index()
    {

        // $wovens = DB::table('design_cards')->select('design_cards.*','customer_masters.first_name','customer_masters.last_name')
        // ->join('customer_masters', 'customer_masters.id', '=', 'design_cards.customer_id')
        // ->orderBy('created_at', 'DESC')
        // ->paginate(config("motorTraders.paginate.perPage"));

      // echo "<pre>"; print_r($wovens);exit;

        $wovens = DesignCard::with(['salesRepDetail','customerDetail'])->paginate(5);
        return view("woven.index", compact("wovens"));
    }


    public function create()
    {   
        $data = $this->mastersDatas();
        $editdesignCard = "";
        return view("woven.create", compact("data","editdesignCard"));
    }

//    WovenRequest
    public function store(Request $request)
    {
        try {

        //    $validatedFields = $this->addOrEditRequest($request);
             $validatedFields                        = $request->all();
        $validatedFields['weaver']              = json_encode($request->weaver);
        $validatedFields['main_label']          = json_encode($request->main_label);
        $validatedFields['tab_label']           = json_encode($request->tab_label);
        $validatedFields['size_label']          = json_encode($request->size_label);
        $validatedFields['add_on_main_cost']    = json_encode($request->main_cost);
        $validatedFields['add_on_tab_cost']     = json_encode($request->tab_cost);
        $validatedFields['add_on_size_cost']    = json_encode($request->size_cost);
        $validatedFields['needle']              = json_encode($request->needle);
            $result = DesignCard::create($validatedFields);

          
            // $validatedFields = $request->validated();
            // unset($validatedFields["image"]);

            // $result = DesignCard::create([
            //     'date' => $request->date,
            //     'customer_id' => $request->customer,
            //     'lable' => $request->label,
            //     'designer_id' => $request->designer,
            //     'design_number' => $request->design_no,
            //     'salesrep_id' => $request->sales_rep,
            //     'weaver_id' => $request->warp,
            //     'warps_id' => $request->warp,
            //     'picks' => $request->pick,
            //     'total_picks' => $request->total_pick,
            //     'loom_id' => json_encode($request->looms),
            //     'total_repet' => json_encode($request->total_repeat),
            //     'wastage' => $request->wastage,
            //     'finishing_id' => $request->finishings,
            //     'cost_in_roll' => $request->cast_inch,
            //     'total_cost' => $request->total_area,
            //     'catagory' => $request->catagory,
            //     'length' => $request->length,
            //     'sq_inch'=> $request->sq_inch,
            //     'customer_grade'=> $request->customer_grade,
            //     'width' =>$request->width,
            //     'add_on_cast'=>json_encode($request->add_on_cast),
            //     'needle'=>json_encode($request->needle)
            // ]);

            // if ($request->hasFile("document_name")) {
            //     $file = $request->file("document_name");
            //     $filePath = $file->store("{$result->id}", [
            //         "disk" => "cardsDocuments",
            //     ]);
            // }

            // if (isset($filePath)) {
            //     $result->update([
            //         "document_name" => $filePath,
            //     ]);
            // }
            // if ($request->hasFile("crap_image")) {
            //     $file = $request->file("crap_image");
            //     $filePath = $file->store("{$result->id}", [
            //         "disk" => "cardsImage",
            //     ]);
            // }

            // if (isset($filePath)) {
            //     $result->update([
            //         "crap_image" => $filePath,
            //     ]);
            // }
        return redirect()
                ->route("woven.index")
                ->with("success", "Design card created successfully.");
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
            ->back()
            ->with(
                "danger",
                "Something went wrong" . $exception->getMessage()
            );
        }
        
    } 
    
    
    public function edit(DesignCard $woven)
    {   
        $data           = $this->mastersDatas();
        $editdesignCard = DesignCard::where('id',$woven->id)->first();
        return view("woven.create", compact("data","editdesignCard"));
    }

    public function update(Request $request, DesignCard $woven)
    {
        try {
            $validatedFields = $this->addOrEditRequest($request);
            // dd($validatedFields['needle'] );
            $woven->update($validatedFields);
            // $validatedFields = $request->validated();
            // unset($validatedFields["image"]);

            // $woven->update($request->all());

            // if ($request->hasFile("image")) {
            //     $file = $request->file("image");
            //     $filePath = $file->store("{$woven->id}/business_document", [
            //         "disk" => "folds",
            //     ]);
            // }

            // if (isset($filePath)) {
            //     $fold->update([
            //         "image" => $filePath,
            //     ]);
            // }

           

            return redirect()
        ->route("woven.index")
        ->with("success", "Desgin card updated successfully.");
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with(
                    "danger",
                    "Something went wrong" . $exception->getMessage()
                );
        }
    }
    
    public function show($woven)
    {
        $viewDesignCard = DesignCard::with(['customerDetail','designerDetail','salesRepDetail','warpDetail','finishingDetail'])->findOrFail($woven);
        return view("woven.show", compact("viewDesignCard"));
    }


    public function destroy(DesignCard $woven)
    {
        $woven->delete(); 
        return redirect()
        ->route("woven.index")
        ->with("success", "Design card deleted successfully.");
    }

    public function addOrEditRequest($request)
    {
        $validatedFields                        = $request->all();
        $validatedFields['weaver']              = json_encode($request->weaver);
        $validatedFields['main_label']          = json_encode($request->main_label);
        $validatedFields['tab_label']           = json_encode($request->tab_label);
        $validatedFields['size_label']          = json_encode($request->size_label);
        $validatedFields['add_on_main_cost']    = json_encode($request->main_cost);
        $validatedFields['add_on_tab_cost']     = json_encode($request->tab_cost);
        $validatedFields['add_on_size_cost']    = json_encode($request->size_cost);
        $validatedFields['needle']              = json_encode($request->needle);
        
        return $validatedFields;
    }

    public function mastersDatas()
    {
        $data['customerMaster']   = CustomerMaster::orderBy('created_at', 'DESC')->get()->toArray();
        $data['loomMaster']       = LoomMaster::orderBy('created_at', 'DESC')->get()->toArray();
        $data['designerMaster']   = Staf_master::where('role_id',1)->orderBy('created_at', 'DESC')->get()->toArray();
        $data['salesrepMaster']   = Staf_master::where('role_id',2)->orderBy('created_at', 'DESC')->get()->toArray();
        $data['warpMaster']       = WarpMaster::orderBy('created_at', 'DESC')->get()->toArray();
        $data['finishingMaster']  = FinishingMachineMaster::orderBy('created_at', 'DESC')->get()->toArray();
        $data['wovenQuality']     = WovenQuality::get()->toArray();
        return $data;
    }
}
