<?php

namespace App\Http\Controllers;

use App\Product;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Log;

class MerchantController extends Controller {
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        // User making request to this controller must be authenticated.
        $this->middleware('auth');
    }
    
    public function registerProduct(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'author' => 'required|max:50',
            'type' => 'required|max:20',
            'price' => 'required|numeric',
            'detail' => 'required|array',
            'detail.url' => 'url|nullable',
            'picture' => 'nullable|file|dimensions:min_width=100,min_height=200|mimetypes:image/jpeg|max:2000',
            'poster' => 'nullable|file|dimensions:min_width=100,min_height=200|mimetypes:image/jpeg|max:5000',
            'book_example' => 'nullable|file|mimetypes:application/pdf|max:11000',
            'book_type' => 'required_if:type,หนังสือ',
            'book_subject' => 'required_if:type,หนังสือ|array',
            'owner_detail_1' => 'required|array',
            'owner_detail_2' => 'required|array',
            'payment' => 'required|array'
        ]);
        if ($request->has('id')) {
            $product = Product::find($request->input('id'));
            if ($product->user_id != Auth::id() AND Gate::denies('admin-action')) {
                abort(403);
            }
        } else {
            $product = new Product();
            $product->user_id = Auth::user()->id;
            $product->status = 'PENDING';
        }
        $product->fill(($request->input('type') == 'หนังสือ') ? $request->all() : $request->except(['book_type', 'book_subject']))->save();
        if (!$request->hasFile('picture') AND empty($product->picture)) {
            return back()->withErrors('ต้องแนบรูปภาพสินค้า');
        }
        foreach (['picture', 'poster', 'book_example'] as $thing) {
            if ($request->hasFile($thing)) {
                $product->$thing = $request->file($thing)->storePubliclyAs('product', $product->id . '-' . $thing.'.'.$request->file($thing)->extension(), 'public');
            }
        }
        $product->save();
        Log::info('User '.Auth::id().' has edited product '.$product->id.': '.$product->name);
        
        return redirect('/product/'.$product->id);
    }
}