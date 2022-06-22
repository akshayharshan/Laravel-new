<?php

namespace App\Http\Controllers;


use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ListingController extends Controller
{
    public function index(){
   
        return view('listings.index',[
            'listings'=>Listing::latest()->filter(request(['tag','search']))->Paginate(6)
        ]);
    }

    public function show(Listing $listing){
        return view('listings.show',[
            'listing'=> $listing
        ]);
    }

    public function create(){
        return view('listings.create');
    }

    public function store(Request $request){

       $formFields = $request->validate([

        'title' => 'required',
        'company' =>['required', Rule::unique('listings','company')],
        'location'=>'required',
        'website'=>'required',
        'email'=>['required','email'],
        'tags'=>'required',
        'description'=>'required'



       ]);

       if ($request->hasFile('logo')){

        $formFields['logo'] = $request->file('logo')->store('logos','public');

       }
       $formFields['user_id'] = auth()->id();
       Listing::create($formFields);
       return redirect('/')->with('message','Listing created successfully!');
        
    }
    public function edit(Listing $listing ){
    // dd($listing->description);
        return view('listings.edit', ['listing'=> $listing]);
    }
    public function update(Request $request ,Listing $listing){

        if($listing->user_id != auth()->id()){

            abort(403,'unauthorized');
        }

        $formFields = $request->validate([

            'title' => 'required',
            'company' =>['required'],
            'location'=>'required',
            'website'=>'required',
            'email'=>['required','email'],
            'tags'=>'required',
            'description'=>'required'
    
    
    
           ]);
    
           if ($request->hasFile('logo')){
    
            $formFields['logo'] = $request->file('logo')->store('logos','public');
    
           }

           
           $listing->update($formFields);
           return back()->with('message','Listing updated successfully!');
    }


    public function delete(Request $request,Listing $listing ){
        // dd($listing->description);
        
        if($listing->user_id != auth()->id()){

            abort(403,'unauthorized Action');
        }
        $listing->delete();
        return redirect('/')->with('message','Listing deleted successfully!');
        
    }

    public function manage(){

        $test = new User();


        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }

    public function getAllListing(){
        // $email = $request->email;
        // $listing = Listing::where('email',$email)->get();
        // return response()->json(['data'=> $listing]);

        return response()->json([
            'status' => true,
            'message' => "Post Updated successfully!",
        ], 200);
    }

    public function storeListing(Request $request){

       
 

        $listing = new Listing();
        $listing->user_id = $request->user_id;
        $listing->title = $request->title;
        $listing->tags = $request->tags;
        $listing->company = $request->company;
        $listing->location = $request->location;
        $listing->email = $request->email;
        $listing->description = $request->description;
        $listing->website = $request->website;
        $listing->create();

        return response()->json(['data'=> $listing]);


    }

}
