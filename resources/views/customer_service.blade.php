@extends(\Illuminate\Support\Facades\Auth::guard('admin')->check() ?'layout.admin_app':'layout.app')

@section('title')
    Customer Service
@endsection
@section('content')
   <div class="card mt-3">
       <div class="card-header">
           <div class="card-title text-center">
               <h1 style="color: #dc4b22">Customer Service</h1>
           </div>
       </div>
       <div class="card-body">
           <div class="card" id="carousel-container">
               <div class="card-body p-3">
                   <h5 class="text-center">Products & Prices</h5>
                   <div class="text-center" style="font-size: 15px;">
                       <p style="color: #dc4b22;font-size: 15px;">Are there any hidden costs or charges if i buy from Pumbashopping ?</p>
                       <p style="font-size: 15px;">There are no hidden charges when you buy from Pumbashopping. All cost are 100% visible on the main page .</p>
                       <p style="color: #dc4b22;font-size: 15px;">Are the prices on Pumbashopping are negotiable ?</p>
                       <p style="font-size: 15px;">Prices on Pumbashopping are not negotiable. Pumbashopping offer you the best prices and deals.</p>
                   </div>
               </div>
           </div>
           <div class="card mt-3" id="carousel-container">
               <div class="card-body p-3">
                   <h5 class="text-center">Products Information</h5>
                   <div class="text-center">
                       <p style="color: #dc4b22;font-size: 15px;">Are all products on Pumbashopping are original and genuine ?</p>
                       <p style="font-size: 15px;">Yes, we are committed to offering our customers only 100% genuine and original products. </p>
                       <p style="color: #dc4b22;font-size: 15px;">Where can i find more detail information about a product ?</p>
                       <p style="font-size: 15px;">Information regarding your product is described in the "Key Features" section at the top of the product page.
                           The detailed information can be found under the "Description" and "Specifications" tabs on the product pages.</p>
                       <p style="color: #dc4b22; font-size: 15px;">Are all products on Pumbashopping are new and unused ?</p>
                       <p style="font-size: 15px;">Yes, Pumbashopping only offers 100% new and unused products.</p>
                   </div>
               </div>
           </div>
       </div>
   </div>
@endsection
