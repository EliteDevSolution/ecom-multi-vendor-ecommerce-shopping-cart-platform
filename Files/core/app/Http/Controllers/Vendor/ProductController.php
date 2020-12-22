<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Product;
use App\Order;
use App\Orderedproduct;
use App\PreviewImage;
use App\Category;
use App\Option;
use Carbon\Carbon;
use App\Subcategory;
use App\ProductAttribute;
use App\FlashInterval;
use App\Vendor;
use DB;
use Auth;
use Validator;
use Image;
use Artisan;
use Session;

class ProductController extends Controller
{
    public function create() {
      $data['flashints'] = FlashInterval::all();
      $data['cats'] = Category::where('status', 1)->get();
      $data['subcats'] = Subcategory::where('status', 1)->get();
      $vendor = Vendor::find(Auth::guard('vendor')->user()->id);
      if ($vendor->products != 0) {
        $today = new \Carbon\Carbon(Carbon::now());
        $existingVal = new \Carbon\Carbon($vendor->expired_date);
        if ($today->gt($existingVal)) {
          $vendor->products = 0;
          $vendor->expired_date = NULL;
          $vendor->save();
          send_email( $vendor->email, $vendor->shop_name, "Subscription Package Expired!", "Your subscription package is expired. Please buy new subscription package.");
        }
      }
      return view('vendor.product.create', $data);
    }

    public function store(Request $request) {

      // return $request->all();
      $imgs = $request->file('images');
      $allowedExts = array('jpg', 'png', 'jpeg');
      $slug = str_slug($request->title, '-');

      $vendor = Vendor::find(Auth::guard('vendor')->user()->id);
      if ($vendor->products == 0) {
        return "no_product";
      }

      $rules = [
        'images' => [
          'required',
          function($attribute, $value, $fail) use ($imgs, $allowedExts) {
              foreach($imgs as $img) {
                  $ext = $img->getClientOriginalExtension();
                  if(!in_array($ext, $allowedExts)) {
                      return $fail("Only png, jpg, jpeg images are allowed");
                  }
              }
              if (count($imgs) > 5) {
                return $fail("Maximum 5 images can be uploaded");
              }
          },
        ],
        'title' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->filled('title')) {
                  $fail('Title is required.');
              }
          }
        ],
        'price' => [
          function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('price')) {
                $fail('Price is required.');
            }
            if ($request->filled('price')) {
              if (!is_numeric($request->price)) {
                $fail('Price must be a number.');
              }
            }
          },
        ],
        'quantity' => [
          function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('quantity')) {
                $fail('Quantity is required.');
            }
            if ($request->filled('quantity')) {
              if (!is_numeric($request->quantity)) {
                $fail('Quantity must be a number.');
              }
            }
          },
        ],
        'cat_helper' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->filled('category')) {
                  $fail('Category is required.');
              }
          }
        ],
        'subcat_helper' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->filled('subcategory')) {
                  $fail('Subcategory is required.');
              }
          }
        ],
        'description' => [
          'required',
        ],
        'offer_amount' => [
          'required_if:offer,1',
        ],
        'flash_amount' => [
          'required_if:flash_sale,1',
        ],
        'flash_date' => [
          'required_if:flash_sale,1',
        ]
      ];

      $messages = [
        'offer_amount.required_if' => 'Offer amount field is required',
        'flash_amount.required_if' => 'Flash amount field is required',
        'flash_date.required_if' => 'Flash date field is required',
      ];

      if ($request->has('subcategory')) {
        $subcat = Subcategory::find($request->subcategory);
        $attrjson = json_decode($subcat->attributes, true);

        if (!array_key_exists('attributes', $attrjson)) {
          $errproattr = '';
        }
        // if subcategory contains no proattr
        else {
          $attrarrs = $attrjson['attributes'];
          $errproattr = [];
          foreach ($attrarrs as $key => $attrarr) {
            $proattr = ProductAttribute::find($attrarr);
            if (!$request->has("$proattr->attrname")) {
              $errproattr["$proattr->attrname"] = "$proattr->name is required";
            }
          }
          // if proattr has no error
          if (empty($errproattr)) {
            $errproattr = '';
          }
        }
      }
      // if there is no subcat given
      else {
        $errproattr = '';
      }


      $validator = Validator::make($request->all(), $rules, $messages);
      if ($validator->fails() || !empty($errproattr)) {
        $errmsgs = $validator->getMessageBag()->add('error', 'true');
        if (!empty($errproattr)) {
          $errmsgs->add('proattr', $errproattr);
        }
        return response()->json($validator->errors());
      }

      $in = $request->only('title','price','description','quantity', 'offer_amount', 'flash_amount', 'flash_date', 'flash_interval');
      $in['vendor_id'] = Auth::guard('vendor')->user()->id;
      $in['slug'] = $slug;
      $in['category_id'] = $request->category;
      $in['subcategory_id'] = $request->subcategory;
      $in['flash_request_date'] = new \Carbon\Carbon(Carbon::now());
      $in['flash_sale'] = $request->has('flash_sale') ? 1 : 0;
      $in['flash_type'] = $request->has('flash_type') ? 1 : 0;
      if ($request->has('offer')) {
        $in['offer_type'] = $request->has('offer_type') ? 'percent' : 'fixed';
        // if offer type percentage
        if ($in['offer_type'] == 'percent') {
          // price - $request->offer_amount*price/100
          $in['current_price'] = $request->price - (($request->offer_amount*$request->price)/100);
        }
        // if offer type fixed
        if ($in['offer_type'] == 'fixed') {
          // price - $request->offer_amount
          $in['current_price'] = $request->price - $request->offer_amount;
        }
      } else {
        $in['current_price'] = NULL;
      }
      if (empty($in['current_price'])) {
        $in['search_price'] = $in['price'];
      } else {
        $in['search_price'] = $in['current_price'];
      }
      if ($request->filled('product_code')) {
        $in['product_code'] = $request->product_code;
      } else {
        $in['product_code'] = product_code(8);
      }
      $in['attributes'] = json_encode($request->except('_token','cat_helper','subcat_helper','images','title','price','category','subcategory','product_code','description', 'quantity', 'offer', 'offer_type', 'offer_amount', 'flash_sale', 'flash_type', 'flash_amount', 'flash_date', 'flash_interval'));

      $product = Product::create($in);

      foreach($imgs as $img) {
        $pi = new PreviewImage;
        $filename = uniqid() . '.jpg';
        $filename1 = uniqid() . '.jpg';
        $location = 'assets/user/img/products/' . $filename;
        $location1 = 'assets/user/img/products/' . $filename1;

        $background = Image::canvas(570, 570);
        $resizedImage = Image::make($img)->resize(570, 570, function ($c) {
            $c->aspectRatio();
        });
        // insert resized image centered into background
        $background->insert($resizedImage, 'center');
        // save or do whatever you like
        $background->save($location);


        $background1 = Image::canvas(1140, 1140);
        $resizedImage1 = Image::make($img)->resize(1140, 1140, function ($c) {
            $c->aspectRatio();
        });
        // insert resized image centered into background
        $background1->insert($resizedImage1, 'center');
        // save or do whatever you like
        $background1->save($location1);

        $pi = new PreviewImage;
        $pi->product_id = $product->id;
        $pi->image = $filename;
        $pi->big_image = $filename1;
        $pi->save();
      }

      $vendor->products = $vendor->products - 1;
      if ($vendor->products == 0) {
        $vendor->expired_date = NULL;
      }
      $vendor->save();

      Session::flash('success', 'Product uploaded successfully!');
      return "success";

    }


    public function getsubcats(Request $request) {
      $subcats = Subcategory::where('category_id', $request->catid)->where('status', 1)->get();
      return $subcats;
    }

    public function getattributes(Request $request) {
      $subcat = Subcategory::find($request->subcatid);
      $attrs = json_decode($subcat->attributes, true);
      if (!empty($attrs['attributes'])) {
        $options = Option::whereIn('product_attribute_id', $attrs['attributes'])->get();
        $productattrs = ProductAttribute::whereIn('id', $attrs['attributes'])->get();
        $sameattroptions = Option::groupBy('product_attribute_id')->whereIn('product_attribute_id', $attrs['attributes'])->select(DB::raw('count(product_attribute_id) as attrcount'))->get();

        $countoptons = [];
        for ($i=0; $i < count($sameattroptions); $i++) {
          $countoptons[] = $sameattroptions[$i]->attrcount;
        }
        return response()->json(['iteratorattr' => count($attrs['attributes']), 'iteratoroptions' => $countoptons, 'productattrs' => $productattrs, 'options' => $options]);

      } else {
        return 'no_attr';
      }

    }

    public function manage() {
      $data['products'] = Product::where('vendor_id', Auth::guard('vendor')->user()->id)->orderBy('id', 'DESC')->where('deleted', 0)->paginate(10);

      return view('vendor.product.manage', $data);
    }

    public function edit($id) {
      $data['flashints'] = FlashInterval::all();
      $data['product'] = Product::find($id);
      if ($data['product']->vendor_id != Auth::guard('vendor')->user()->id) {
        return back();
      }
      $data['checkedattrs'] = json_decode($data['product']->attributes, true);
      $data['attrs'] = json_decode(Subcategory::find($data['product']->subcategory_id)->attributes, true);
      $data['cats'] = Category::where('status', 1)->get();
      $data['subcats'] = Subcategory::where('category_id', $data['product']->category_id)->get();
      return view('vendor.product.edit', $data);
    }

    public function getimgs($id) {
      $preimgs = PreviewImage::where('product_id', $id)->get();
      return $preimgs;
    }

    public function update(Request $request) {
      if ($request->hasFile('images')) {
        $imgs = $request->file('images');
      } else {
        $imgs = [];
      }
      if (!$request->has('imgsdb')) {
        $request->imgsdb = [];
      }
      $allowedExts = array('jpg', 'png', 'jpeg');
      $slug = str_slug($request->title, '-');

      $rules = [
        'imgs_helper' => [
          function($attribute, $value, $fail) use ($imgs, $allowedExts, $request) {
              if (count($request->imgsdb) == 0 && count($imgs) == 0) {
                return $fail("Preview image is required");
              }
              foreach($imgs as $img) {
                $ext = $img->getClientOriginalExtension();
                if(!in_array($ext, $allowedExts)) {
                    return $fail("Only png, jpg, jpeg images are allowed");
                }
              }
              if ((count($imgs)+count($request->imgsdb)) > 5) {
                return $fail("Maximum 5 images can be uploaded");
              }
          },
        ],
        'title' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->filled('title')) {
                  $fail('Title is required.');
              }
          }
        ],
        'price' => [
          function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('price')) {
                $fail('Price is required.');
            }
            if ($request->filled('price')) {
              if (!is_numeric($request->price)) {
                $fail('Price must be a number.');
              }
            }
          },
        ],
        'quantity' => [
          function ($attribute, $value, $fail) use ($request) {
            if (!$request->filled('quantity')) {
                $fail('Quantity is required.');
            }
            if ($request->filled('quantity')) {
              if (!is_numeric($request->quantity)) {
                $fail('Quantity must be a number.');
              }
            }
          },
        ],
        'cat_helper' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->filled('category')) {
                  $fail('Category is required.');
              }
          }
        ],
        'subcat_helper' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->filled('subcategory')) {
                  $fail('Subcategory is required.');
              }
          }
        ],
        'description' => [
          'required',
        ],

      ];

      if ($request->has('subcategory')) {
        $subcat = Subcategory::find($request->subcategory);
        $attrjson = json_decode($subcat->attributes, true);

        if (!array_key_exists('attributes', $attrjson)) {
          $errproattr = '';
        }
        // if subcategory contains no proattr
        else {
          $attrarrs = $attrjson['attributes'];
          $errproattr = [];
          foreach ($attrarrs as $key => $attrarr) {
            $proattr = ProductAttribute::find($attrarr);
            if (!$request->has("$proattr->attrname")) {
              $errproattr["$proattr->attrname"] = "$proattr->name is required";
            }
          }
          // if proattr has no error
          if (empty($errproattr)) {
            $errproattr = '';
          }
        }
      }
      // if there is no subcat given
      else {
        $errproattr = '';
      }


      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails() || !empty($errproattr)) {
        $errmsgs = $validator->getMessageBag()->add('error', 'true');
        if (!empty($errproattr)) {
          $errmsgs->add('proattr', $errproattr);
        }
        return response()->json($validator->errors());
      }

      $in = $request->only('title', 'price', 'description','quantity', 'offer_amount', 'flash_amount', 'flash_date', 'flash_interval');
      $in['vendor_id'] = Auth::guard('vendor')->user()->id;
      $in['category_id'] = $request->category;
      $in['subcategory_id'] = $request->subcategory;
      $in['slug'] = $slug;
      $in['flash_request_date'] = new \Carbon\Carbon(Carbon::now());
      $in['flash_sale'] = $request->has('flash_sale') ? 1 : 0;
      $in['flash_type'] = $request->has('flash_type') ? 1 : 0;
      if ($request->has('offer')) {
        // if offer type percentage
        if ($request->has('offer_type')) {
          $in['offer_type'] = 'percent';
          $in['current_price'] = $request->price - (($request->offer_amount*$request->price)/100);
        } else {
          $in['offer_type'] = 'fixed';
          $in['current_price'] = $request->price - $request->offer_amount;
        }
      } else {
        $in['current_price'] = NULL;
        $in['offer_type'] = NULL;
        $in['offer_amount'] = NULL;
      }
      if (empty($in['current_price'])) {
        $in['search_price'] = $in['price'];
      } else {
        $in['search_price'] = $in['current_price'];
      }
      if ($request->filled('product_code')) {
        $in['product_code'] = $request->product_code;
      } else {
        $in['product_code'] = product_code(8);
      }

      $in['attributes'] = json_encode($request->except('_token','cat_helper','subcat_helper','images','imgsdb','title','price','category','subcategory','product_code','description','quantity','imgs_helper','product_id', 'offer', 'offer_type', 'offer_amount', 'flash_sale', 'flash_type', 'flash_amount', 'flash_date', 'flash_interval'));
      $product = Product::find($request->product_id);
      $product->fill($in)->save();

      // bring all the product images of that product
      $productimgs = PreviewImage::where('product_id', $product->id)->get();

      // then check whether a filename is missing in imgsdb if it is missing remove it from database and unlink it
      foreach($productimgs as $productimg) {
        if(!in_array($productimg->image, $request->imgsdb)) {
            @unlink('assets/user/img/products/'.$productimg->image);
            @unlink('assets/user/img/products/'.$productimg->big_image);
            $productimg->delete();
        }
      }
      foreach($imgs as $img) {
        $pi = new PreviewImage;
        $filename = uniqid() . '.jpg';
        $filename1 = uniqid() . '.jpg';
        $location = 'assets/user/img/products/' . $filename;
        $location1 = 'assets/user/img/products/' . $filename1;

        $background = Image::canvas(570, 570);
        $resizedImage = Image::make($img)->resize(570, 570, function ($c) {
            $c->aspectRatio();
        });
        // insert resized image centered into background
        $background->insert($resizedImage, 'center');
        // save or do whatever you like
        $background->save($location);


        $background1 = Image::canvas(1140, 1140);
        $resizedImage1 = Image::make($img)->resize(1140, 1140, function ($c) {
            $c->aspectRatio();
        });
        // insert resized image centered into background
        $background1->insert($resizedImage1, 'center');
        // save or do whatever you like
        $background1->save($location1);

        $pi = new PreviewImage;
        $pi->product_id = $product->id;
        $pi->image = $filename;
        $pi->big_image = $filename1;
        $pi->save();
      }

      return "success";

    }

    public function delete(Request $request) {
      $product = Product::find($request->id);
      $product->deleted = 1;
      $product->save();

      Session::flash('success', 'Product removed successfully!');

      return "success";
    }
}
