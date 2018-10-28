<?php

namespace Modules\Projects\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Projects\Entities\LandUnits;
use Modules\Countries\Entities\Countrylangs;
use Yajra\DataTables\DataTables;
use \Validator;
use \Illuminate\Support\Facades\Session;

use Modules\Countries\Entities\Countries;


class AdminLandUnitController extends Controller
{

/**
    * Display a listing of the resource.
    * @return Response
*/

    public function index()
    {
        return view('projects::admin.landunits.index');
    }

/**
    * Display a listing of the resource.
    * @return Response
*/
	public function alllandunits()
	{
		return Datatables::of(LandUnits::with('created_language')->where('language_id',1)->orderBy('id', 'DESC')->get())->make(true);
	}

/**
    * Show the form for creating a new resource.
    * @return Response
*/

    public function create()
    {
		$countries=Countries::with(['created_countries'=>function($query) {$query->where('status', 1);}])->get();
        $languages = Countrylangs::with('languages')->where('language_id','!=',1)->get();
        return view('projects::admin.landunits.create',compact('languages','countries'));
        
    }

/**
    * Store a newly created resource in storage.
    * @param  Request $request
    * @return Response
*/

    public function store(Request $request)
    {
		//$this->validate($request, ['unit_en' => 'required|unique_land_unit:property_land_units,land_unit,NULL,id']);
        
        $desc_language = $request->desc_language;
        $title_entrys= 'unit_'.$request->desc_language[0];
        $desc_entrys= 'slug_'.$request->desc_language[0];
        

        $landunits = new LandUnits;
        $landunits->language_id = $desc_language[0];
        $landunits->country_id = $request->countries;
        $landunits->land_unit = $_POST[$title_entrys];
        $landunits->slug = $_POST[$desc_entrys];   
        $landunits->status = $request->status_en;

        try {
            $units = $request->unit;
            $landunits->save();
            $id = $landunits->id;
            /*if(!empty($units) && $units!=''){
                $languages = $request->language;
                $slugs = $request->slug;
                foreach($units as $key => $value):
                    $landunits = new LandUnits;
                    $landunits->parent_id = $id;
                    $landunits->language_id = $languages[$key];
                    $landunits->land_unit = $units[$key];
                    $landunits->slug = $slugs[$key];
                    $landunits->status = $request->status_en;
                    $landunits->save();
                endforeach;
            }*/

            if($request->desc_language){
                for($i=1;$i< count($desc_language); $i++)
                {
                            $unit_entry = 'unit_'.$desc_language[$i];
                            $slug_entry = 'slug_'.$desc_language[$i];
                            

                            $landunits = new LandUnits;
                            $landunits->parent_id = $id;
                            $landunits->language_id = $desc_language[$i];
                            $landunits->land_unit = $_POST[$unit_entry];
                            $landunits->slug =$_POST[$slug_entry];
                            $landunits->status = $request->status_en;
                            $landunits->country_id = $request->countries;
                            $landunits->save();

                }
            }

            $request->session()->flash('val', 1);
            $request->session()->flash('msg', "Land Unit created successfully !");
            return response()->json(['status'=>true,'url'=>URL('/o4k/projects/land_unit/'),'csrf' => csrf_token()]);
        }
        catch (\Exception $e) {
            $request->session()->flash('val', 0);
            $request->session()->flash('msg', "Land Unit not created successfully.".$e->getMessage()); 
            return response()->json(['status'=>false,'csrf' => csrf_token()]);
   
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $land_unit = LandUnits::with('types')->find($id);
        if($land_unit==null){return redirect('/o4k/404');}
        else{
            $countries=Countries::with(['created_countries'=>function($query) {$query->where('status', 1);}])->get();
            $languages=Countrylangs::with('languages')->where('created_country_id',$land_unit->country_id)->orderBy('language_id', 'ASC')->get()->toArray();

            

            return view('projects::admin.landunits.edit',compact('languages','land_unit','countries'));
        }
        
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id,Request $request)
    {
		/*$this->validate($request, [
            'unit_en' => "required|unique_land_unit:property_land_units,land_unit,$id,id"
        ]);*/

        $landunits = LandUnits::find($id);
        if($landunits==null){ 
            $request->session()->flash('val', 0);
            $request->session()->flash('msg', "Land unit not updated successfully.".$e->getMessage()); 
            return response()->json(['status'=>false,'csrf' => csrf_token()]);
        }
        else
        {
            $desc_language = $request->desc_language;
            $title_entrys= 'unit_'.$request->desc_language[0];
            $desc_entrys= 'slug_'.$request->desc_language[0];

            $landunits->language_id = $desc_language[0];
            $landunits->country_id = $request->countries;
            $landunits->land_unit = $_POST[$title_entrys];
            $landunits->slug = $_POST[$desc_entrys];   
            $landunits->status = $request->status_en;

            try
            {
                $landunits->save();
                $ids = $request->ids;
                /*$units = $request->unit;
                if(!empty($units) && $units!=''){
                    $languages = $request->language;
                    $slugs = $request->slug; 
                    foreach($ids as $key => $value):
                        $landunits = LandUnits::find($value);
                        $landunits->parent_id = $id;
                        $landunits->language_id = $languages[$key];
                        $landunits->land_unit = $units[$key];
                        $landunits->slug = $slugs[$key];
                        $landunits->status = $request->status_en;
                        $landunits->save();
                    endforeach;
                }*/

                LandUnits::where('parent_id',$id)->get()->each->delete();

                if($request->desc_language){
                for($i=1;$i< count($desc_language); $i++)
                {
                            $unit_entry = 'unit_'.$desc_language[$i];
                            $slug_entry = 'slug_'.$desc_language[$i];
                            
                            $landunits = new LandUnits;
                            $landunits->parent_id = $id;
                            $landunits->language_id = $desc_language[$i];
                            $landunits->land_unit = $_POST[$unit_entry];
                            $landunits->slug =$_POST[$slug_entry];
                            $landunits->status = $request->status_en;
                            $landunits->country_id = $request->countries;
                            $landunits->save();

                }
            }
                $request->session()->flash('val', 1);
                $request->session()->flash('msg', "Land Unit updated successfully !");
                return response()->json(['status'=>true,'url'=>URL('/o4k/projects/land_unit/'),'csrf' => csrf_token()]);
            }
            catch (\Exception $e) {
               $request->session()->flash('val', 0);
                $request->session()->flash('msg', "Land Unit not updated successfully.".$e->getMessage()); 
                return response()->json(['status'=>false,'csrf' => csrf_token()]);
            }
        }
        
    }

/**
    * Remove the specified resource from storage.
    * @return Response
*/

   public function destroy($id)
    {
        $landunits = LandUnits::find($id);
        if($landunits==null){return redirect('/o4k/404');}
        else
        { 
            try
            { 
                $landunits->delete();
                
                LandUnits::where('parent_id',$id)->get()->each->delete();


                Session::flash('val', 1);
                Session::flash('msg', "Land Unit deleted successfully !");

            } catch (Exception $ex) {
                Session::flash('val', 1);
                Session::flash('msg', $ex->getMessage());
            } 
            return redirect('/o4k/projects/land_unit');
        }
    }

/**
    * Activate resource.
    * @return Response
*/

    public function activate($id)
    {
        $landunits = LandUnits::find($id);
        if($landunits==null){return redirect('/o4k/404');}
        else
        { 
            try
            { 
            
                $landunits->status=1;
                $landunits->save();


                 $list = LandUnits::where('parent_id',$id)->get();
                foreach($list as $l)
                {
                    $amenities = LandUnits::find($l->id);
                    $amenities->status=1;
                    $amenities->save();
                }


                Session::flash('val', 1);
                Session::flash('msg', "Land Unit activated successfully !");
            }catch (Exception $ex) {
                Session::flash('val', 1);
                Session::flash('msg', $ex->getMessage());
            } 
            return redirect('/o4k/projects/land_unit');
        }
            
    }

/**
    * Deactivate resource.
    * @return Response
*/

    public function deactivate($id)
    {
        $landunits =  LandUnits::find($id);
        if($landunits==null){return redirect('/o4k/404');}
        else
        { 
            try
            { 
                $landunits->status=0;
                $landunits->save();


                $list = LandUnits::where('parent_id',$id)->get();
                foreach($list as $l)
                {
                    $amenities = LandUnits::find($l->id);
                    $amenities->status=0;
                    $amenities->save();
                }



                Session::flash('val', 1);
                Session::flash('msg', "Land Unit deactivated successfully !");
            }catch (Exception $ex) {
                Session::flash('val', 1);
                Session::flash('msg', $ex->getMessage());
            } 
            return redirect('/o4k/projects/land_unit');
        }
    }


    public function getlanguage($countryid,Request $request)
    {
        if($request->ajax())
        {
            $languages=Countrylangs::with('languages')->where('created_country_id',$countryid)->orderBy('language_id', 'ASC')->get()->toArray();
            if(!empty($languages))
            {  

                 $returnHTML = (String) view('projects::admin.landunits.section.dynamic',compact('languages'));
                return response()->json(['status'=>true, 'html'=>$returnHTML,'csrf' => csrf_token()]);
            }else{return response()->json(['status'=>false, 'message'=>'Oops something went wrong','csrf' => csrf_token()]);}
        }else{return redirect('/404');}
    }


    public function getlanguage_edit($countryid,$parent_id,Request $request)
    {
       
        if($request->ajax())
        {
            $languages=Countrylangs::with('languages')->where('created_country_id',$countryid)->orderBy('id', 'ASC')->get()->toArray();
            if(!empty($languages))
            {  

                 $returnHTML = (String) view('projects::admin.landunits.section.dynamic_edit',compact('languages','parent_id'));
                return response()->json(['status'=>true, 'html'=>$returnHTML,'csrf' => csrf_token()]);
            }else{return response()->json(['status'=>false, 'message'=>'Oops something went wrong','csrf' => csrf_token()]);}
        }else{return redirect('/404');}
    }
}
