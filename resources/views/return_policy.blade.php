@extends(\Illuminate\Support\Facades\Auth::guard('admin')->check() ?'layout.admin_app':'layout.app')

@section('title')
    Return Policy
@endsection
@section('content')
   <div class="card mt-3">
       <div class="card-header">
           <div class="card-title text-center">
               <h1 style="color: #dc4b22">Return Policy</h1>
           </div>
       </div>
       <div class="card-body">
           <div class="card" id="carousel-container">
               <div class="card-body p-3">
                   <div class="text-center" style="font-size: 15px;">
                       <p style="font-size: 15px;">Please read through our Return Policy on this page to understand our return procedures and make sure your item is eligible for return.
                           You will have 7 days after an item is delivered to you to notify us that you want to return the item. This means if your item was delivered e.g. on the 5th of the month, you have till the 12th to initiate a return.
                       The return item will be check, If validated and Quality Check passes, you will be refunded for the item</p>
                   </div>
               </div>
           </div>
       </div>
   </div>
@endsection
