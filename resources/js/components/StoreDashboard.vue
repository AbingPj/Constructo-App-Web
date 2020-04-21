    <template>
<div>
 <myHeader></myHeader>
 <Loading v-if="showAvailabilityLoading  == true"></Loading>
 <Loading v-if="showLoading  == true"></Loading>
  <div class="row" v-if="categoryRequestisSent == true">
     <div class="col-12 text-center">
         <p style="color: #479e48; font-size: 19px;"> <i class="fas fa-check-circle"> </i> Category request succesfully sent. </p>
     </div>
  </div>

    <div class="panel">
    <div class="row ml-3 mr-3 panel-body">
        <div class="col-sm-3">
            <div class="card" id="dashboardCard">
              <div class="card-body">
                     <div class="card-body">
                   <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <!-- start Dashboard -->
                    <a class="nav-link active" id="v-pills-dashboard-tab" @click="gotoOrders()" data-toggle="pill" href="#v-pills-dashboard" role="tab" aria-controls="v-pills-dashboard" aria-selected="true" style="border-radius : 0px !important;" > <i class="fas fa-tachometer-alt"> </i> Dashboard <span class="badge badge-pill badge-danger" > </span></a>
                        <!-- end Dashboard -->
                    <a class="nav-link" id="v-pills-home-tab" @click="gotoOrders()" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" style="border-radius : 0px !important;" v-on:click="orderNotifCount=0"> <i class="fas fa-clipboard-list"> </i> Orders <span class="badge badge-pill badge-danger" v-if="orderNotifCount!=0"> {{orderNotifCount}} </span></a>
                    <a class="nav-link" id="v-pills-products-tab" @click="gotoProducts()" data-toggle="pill" href="#v-pills-products" role="tab" aria-controls="v-pills-products" aria-selected="false" style="border-radius : 0px !important;"> <i class="fas fa-boxes"> </i> Products</a>
                    <a class="nav-link" id="v-pills-settings-tab" @click="gotoSettings()" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false" style="border-radius : 0px !important;"> <i class="fas fa-cog"> </i>  Settings</a>
                    <a class="nav-link" id="v-pills-categories-tab" @click="gotoProductCategories()" data-toggle="pill" href="#v-pills-categories" role="tab" aria-controls="v-pills-categories" aria-selected="false" style="border-radius : 0px !important;"><i class="fas fa-list-ul"> </i> Product Category</a>
                    <a class="nav-link" id="v-pills-notifications-tab" @click="gotoNotifications()" data-toggle="pill" href="#v-pills-notifications" role="tab" aria-controls="v-pills-notifications" aria-selected="false" v-on:click="RequestNotifCount=0" style="border-radius : 0px !important;"><i class="fas fa-bell"> </i> Notifications <span class="badge badge-pill badge-danger" v-if="RequestNotifCount!=0">{{RequestNotifCount}}</span> </a>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="col">

            <!-- My Dashboard -->
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                       <div class="card">
                           <div class="card-header"> <i class="fas fa-tachometer-alt"> </i> My Dashboard </div>
                           <div class="card-body">
                              <div class="row" style="height: 90px;">
                                <div class="col-4" >
                                   <div class="card">
                                       <div class="card-content" style="background-color :rgb(65, 65, 66);color : white; cursor: pointer;" @click="redirectToorders();">
                                       <div class="text-center" style="margin-top: 10px;">
                                           <p class="h1" style="font-weight: 900;"> <i class="fas fa-clipboard-list"></i>  {{orders.length}} </p>
                                           <b class="h5"> Number of all Orders </b>
                                       </div>
                                        </div>
                                   </div>

                                </div>
                                <div class="col-4">
                                    <div class="card">
                                       <div class="card-content" style="background-color :rgb(65, 65, 66);color : white; cursor: pointer;" @click="redirectToProducts()">
                                        <div class="text-center" style="margin-top: 10px">
                                            <p class="h1" style="font-weight: 900;"> <i class="fas fa-boxes"> </i> {{products.length}} </p>
                                            <p class="h5"> Number of all Products </p>
                                        </div>
                                       </div>
                                   </div>
                                </div>
                                <div class="col-4">
                                    <div class="card">
                                       <div class="card-content" style="background-color :rgb(65, 65, 66);color : white; cursor: pointer;" @click="redirectToProductCategories()">
                                        <div class="text-center" style="margin-top: 10px">
                                            <p class="h1" style="font-weight: 900;"> <i class="fas fa-list"> </i> {{categories.length}} </p>
                                            <p class="h5"> Number of all Product Categories </p>
                                        </div>
                                       </div>
                                   </div>
                                </div>
                              </div>

                              <div class="row" style="margin-top: 70px;">
                                  <div class="col-12">
                                    <p style="font-size: 19px;"> Top 3 best selling products </p>
                                    <div class="row" >
                                        <div class="col-4" v-for="(top, index) in top3Products" v-if="index < 3 && top3Products.length != 0">
                                            <div class="card">
                                               <img class="card-img-top" :src="'/uploads/productImages/'+top.image" style="height: 190px;">
                                               <div class="card-body">
                                                    <p style="font-size: 20px"> {{top.name}} </p>
                                                    <p style="font-size: 18px; color: rgb(253, 126, 20); font-weight: bold;" v-if="top.price != 0"> ₱{{top.price}}</p>
                                                    <p style="font-size: 18px; color: rgb(253, 126, 20); font-weight: bold;" v-if="top.price == 0"> Negotiable </p>
                                                    <hr>
                                                    <p style="font-size: 18px;"> Total buys: {{top.totalQty}} </p>
                                               </div>
                                            </div>
                                          </div>
                                          <div class="col-12" v-if="top3Products.length == 0">
                                              <p style="font-size: 14px;"> No products that has been sold yet. </p>
                                          </div>
                                    </div>
                                  </div>

                              </div>
                              <div class="row" style="margin-top: 70px;">
                                  <div class="col-12">
                                    <p style="font-size: 19px;"> Top 3 buyers </p>
                                      <div class="row">
                                          <div class="col-4" v-for="(top, index) in top3Buyers" v-if="index < 3 && top3Buyers.length != 0">
                                            <div class="card">
                                                <img class="card-img-top" :src="'/uploads/productImages/'+top.profilepic">
                                                <div class="card-body">
                                                    <p style="font-size: 18px; text-transform: capitalize;"> {{top.firstname}} {{top.lastname}} </p>
                                                     <hr>
                                                    <p style="font-size: 18px"> {{top.address}} </p>
                                                    <hr>
                                                    <p style="font-size: 18px"> Total transaction: {{top.totalBuyers}} </p>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-12" v-if="top3Buyers.length == 0">
                                              <p style="font-size: 14px;"> No customer transaction yet. </p>
                                          </div>
                                      </div>
                                  </div>
                             </div>
                           </div>
                      </div>
                </div>
            <!-- My Orders-->
                        <div class="tab-pane fade " id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="card">
                                <div class="card-header">
                                       <span style="font-size:16px;"><i class="fas fa-clipboard-list"> </i> My Orders </span>
                                </div>
                                <div class="card-body">
                                      <div class="card mt-1">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12"> <input type="text" placeholder="Search Customer Name..." class="form-control" style="border-radius: 0px;" v-model="orderSearchCustomer"> </div>
                                            </div>
                                          <div class="row mt-2">
                                            <div class="col-4">
                                              <div class="form-group">
                                                <label> Payment Term :</label>
                                                <select class="form-control" style="border-radius: 0px;" v-model="orderFilterTerm" >
                                                  <option :value="1"> Cash Upon Pickup </option>
                                                  <option :value="2"> Cash On Delivery </option>
                                                  <option :value="3"> All </option>
                                                </select>
                                              </div>
                                            </div>
                                            <div class="col-4">
                                              <div class="form-group"  v-if="orderFilterTerm == 3">
                                                <label> Order Status :</label>
                                                <select class="form-control" style="border-radius: 0px;"  v-model="orderFilterCODStatus" >
                                                   <option :value="1"> Pending </option>
                                                   <option :value="2"> Delivered </option>
                                                   <option :value="3"> Fetched </option>
                                                   <option :value="4"> Cancelled </option>
                                                </select>
                                              </div>
                                              <div class="form-group"  v-if="orderFilterTerm == 2">
                                                <label>Order Status :</label>
                                                <select class="form-control" style="border-radius: 0px;"  v-model="orderFilterCODStatus">
                                                   <option :value="1"> Pending </option>
                                                   <option :value="2"> Delivered </option>
                                                   <option :value="4"> Cancelled </option>
                                                </select>
                                              </div>
                                              <div class="form-group"  v-if="orderFilterTerm == 1">
                                                <label> Status :</label>
                                                <select class="form-control" style="border-radius: 0px;"  v-model="orderFilterCUPStatus">
                                                   <option :value="1"> Pending </option>
                                                   <option :value="3"> Fetched </option>
                                                   <option :value="4"> Cancelled </option>
                                                </select>
                                              </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label>Sort Date : </label>
                                                        <select class="form-control" v-model="DateSortKey">
                                                          <option :value="'asc'"> Order due date by Asc </option>
                                                          <option :value="'desc'"> Order due date by Desc</option>
                                                        </select>
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="card mt-2 mb-3"  v-if="orders.length != 0">
                                          <div class="card-header">
                                              <div class="row">
                                            <div class="col"> <span style="font-size: 16px; font-weight: 500;"> Order ID </span> </div>
                                            <div class="col"> <span style="font-size: 16px; font-weight: 500;"> Customer </span></div>
                                            <div class="col"> <span style="font-size: 16px; font-weight: 500;"> Date Ordered</span></div>
                                            <div class="col"> <span style="font-size: 16px; font-weight: 500;"> Due Date</span></div>
                                            <div class="col"> <span style="font-size: 16px; font-weight: 500;"> Payment Term</span></div>
                                             <div class="col"> <span style="font-size: 16px; font-weight: 500;"> Status</span></div>
                                          </div>
                                          </div>
                                      </div>

                                          <div class="card " v-for="order in orders" v-if="orders.length != 0 && orderSearchCustomer == ''" @click="viewSingleOrder(order.orderId)" style="cursor: pointer;">
                                            <div class="card-header">
                                                <div class="row">
                                                   <div class="col">  <span style="font-size: 16px; font-weight: 500;">  #{{order.orderId}} </span> </div>
                                                   <div class="col"> <img :src="'uploads/productImages/'+order.image" style="width: 40px; height: 40px; border-radius: 50%;">   <span style="font-size: 16px; font-weight: 500; text-transform: capitalize;"> {{order.customerName}} </span> </div>
                                                   <div class="col">  <span style="font-size: 16px; font-weight: 500;"> {{order.date_ordered}} </span> </div>
                                                   <div class="col">  <span style="font-size: 16px; font-weight: 500;"> {{order.datedeliver}} </span> </div>
                                                   <div class="col">  <span style="font-size: 16px; font-weight: 500;"> {{order.paymentterm}}  </span> </div>
                                                   <div class="col">
                                                       <span style="font-size: 16px; font-weight: 500; color: #71c44a;" v-if="order.status == 'Fetched' || order.status == 'Delivered'"> {{order.status}}  </span>
                                                       <span style="font-size: 16px; font-weight: 500; color: #e88e10;" v-if="order.status =='Pending'"> {{order.status}}  </span>
                                                       <span style="font-size: 16px; font-weight: 500; color: #ed3636;" v-if="order.status =='Cancelled'"> {{order.status}}  </span>
                                                    </div>
                                                </div>

                                            </div>
                                          </div>
                                            <div class="card" v-for="order in orderFilterCustomerName" v-if="orders.length != 0 && orderSearchCustomer != ''" @click="viewSingleOrder(order.orderId)" style="cursor: pointer;">
                                            <div class="card-header">
                                                <div class="row">
                                                   <div class="col">  <span style="font-size: 16px; font-weight: 500;">  #{{order.orderId}} </span> </div>
                                                   <div class="col">  <span style="font-size: 16px; font-weight: 500; text-transform: capitalize;"> {{order.customerName}} </span> </div>
                                                   <div class="col">  <span style="font-size: 16px; font-weight: 500;"> {{order.date_ordered}} </span> </div>
                                                   <div class="col">  <span style="font-size: 16px; font-weight: 500;"> {{order.datedeliver}} </span> </div>
                                                   <div class="col">  <span style="font-size: 16px; font-weight: 500;"> {{order.paymentterm}}  </span> </div>
                                                   <div class="col">
                                                       <span style="font-size: 16px; font-weight: 500; color: #71c44a;" v-if="order.status == 'Fetched' || order.status == 'Delivered'"> {{order.status}}  </span>
                                                       <span style="font-size: 16px; font-weight: 500; color: #e88e10;" v-if="order.status =='Pending'"> {{order.status}}  </span>
                                                       <span style="font-size: 16px; font-weight: 500; color: #ed3636;" v-if="order.status =='Cancelled'"> {{order.status}}  </span>
                                                    </div>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="row mt-2 justify-content-center" v-if="orders.length != 0 && orderSearchCustomer == ''">
                                             <div class="col-5">
                                               <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchOrdersPaginate(ordersPagination.prev_page_url)" :disabled="!ordersPagination.prev_page_url"> <i class="fas fa-chevron-circle-left"> </i> Prev</button>
                                             </div>
                                             <div class="col-2">
                                               <span> page {{ordersPagination.current_page}} of {{ordersPagination.last_page}}</span>
                                             </div>
                                             <div class="col-5">
                                                  <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchOrdersPaginate(ordersPagination.next_page_url)" :disabled="!ordersPagination.next_page_url"> <i class="fas fa-chevron-circle-right"> </i> Next</button>
                                             </div>
                                           </div>

                                       <div class="card-body" v-if="orders.length == 0">
                                        <div class="row">
                                            <div class="col-12 text-center"> Empty result</div>
                                        </div>
                                       </div>
                                    </div>
                            </div>
                            </div>
                            <!-- My products-->
                    <div class="tab-pane fade" id="v-pills-products" role="tabpanel" aria-labelledby="v-pills-products-tab">
                               <div class="card">
                                    <div class="card-header">
                                       <span style="font-size:16px;"><i class="fas fa-boxes"> </i> My Products </span>
                                    </div>
                                    <div class="card-body" v-if="storeAccess.length != 0">
                                       <div class="row">
                                           <div class="col-7">
                                               <input class="form-control" placeholder="Search Here..." v-model="productSearchName">
                                           </div>
                                           <div class="col">
                                               <button class="btn"  style="color: white; border-radius:0px; background-color:#fd7e14;"><i class="fas fa-search"> </i> Search </button>
                                            <button class="btn " style="color: white; border-radius:0px; background-color:#fd7e14;" v-on:click="launchAddproductModal()"><i class="fas fa-plus-circle"> </i> Add Product </button>
                                           </div>
                                       </div>

                                       <div class="card mt-3" v-for="product in products" v-if="showLoading == false && products.length != 0 && productSearchName == ''">
                                           <div class="card-body">
                                               <img :src="'/uploads/productImages/'+product.image" style="float : left; width:120px !important; height:120px;">
                                                <div class="row ml-3">
                                                 <div class="col-sm-3">
                                                         <span style="font-size: 16px;"> {{product.name}} </span>
                                                   </div>
                                                   <div class="col-sm-2">
                                                      <span style="color: rgb(253, 126, 20); font-size: 22px; font-weight: bold;" v-if="product.price != 0"> ₱{{product.price}} </span>
                                                      <span style="color: rgb(253, 126, 20); font-size: 22px; font-weight: bold;" v-if="product.price == 0"> Negotiable </span>
                                                   </div>
                                                   <div class="col-sm-4">
                                                       <span style="font-size: 16px;"> {{product.description}}</span>
                                                   </div>
                                               </div>
                                               <div class="row">
                                                   <div class="col-3">
                                                       <p>  {{product.category}}</p>
                                                   </div>
                                                   <div class="col-5">
                                                       <b style="color: red;" v-if="product.statusLabel=='unavailable'"><i class="fas fa-times"> </i> {{product.statusLabel}} </b>
                                                       <b style="color : green" v-else><i class="fas fa-check"> </i> {{product.statusLabel}} </b>
                                                   </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col">
                                                        <button class="btn" style="border-radius : 0px !important; background-color:#fd7e14; color: white;" v-on:click="viewSingleProduct(product.id)"><i class="fas fa-edit"> </i>  Edit Product</button>
                                                         <button class="btn" style="border-radius : 0px !important; background-color:#fd7e14; color: white;"  v-on:click="editAvailability(product.id,1);" v-if="product.statusLabel=='unavailable'"> <i class="fas fa-exchange-alt"></i> Set to Available</button>
                                                         <button class="btn" style="border-radius : 0px !important; background-color:#fd7e14; color: white;" v-on:click="editAvailability(product.id,0);" v-else><i class="fas fa-exchange-alt"></i> Set to Unavailable</button>
                                                         <button class="btn" style="border-radius : 0px !important; background-color:#fd7e14; color: white;" v-on:click="launchRemoveProductModal(product.id)"><i class="fas fa-trash"></i> Remove Product</button>
                                                  </div>
                                              </div>
                                           </div>
                                       </div>
                                       <div class="card mt-3" v-for="product in productFilter" v-if="showLoading == false && products.length != 0 && productSearchName != ''">
                                           <div class="card-body">
                                       <img :src="'/uploads/productImages/'+product.image" style="float : left; width:120px !important; height:120px;">

                                               <div class="row ml-3">
                                                 <div class="col-sm-3">
                                                         <span style="font-size: 16px;"> {{product.name}} </span>
                                                   </div>
                                                   <div class="col-sm-2">
                                                      <span style="color: rgb(253, 126, 20); font-size: 22px; font-weight: bold;" v-if="product.price != 0"> ₱{{product.price}} </span>
                                                      <span style="color: rgb(253, 126, 20); font-size: 22px; font-weight: bold;" v-if="product.price == 0"> Negotiable </span>
                                                   </div>
                                                   <div class="col-sm-4">
                                                       <span style="font-size: 16px;"> {{product.description}}</span>
                                                   </div>
                                               </div>
                                               <div class="row">
                                                   <div class="col-3">
                                                       <p>  {{product.category}}</p>
                                                   </div>
                                                   <div class="col-5">
                                                       <b style="color: red;" v-if="product.statusLabel=='unavailable'"><i class="fas fa-times"> </i> {{product.statusLabel}} </b>
                                                       <b style="color : green" v-else><i class="fas fa-check"> </i> {{product.statusLabel}} </b>
                                                   </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col">
                                                        <button class="btn" style="border-radius : 0px !important; background-color:#fd7e14; color: white;" v-on:click="viewSingleProduct(product.id)"><i class="fas fa-edit"> </i>  Edit Product</button>
                                                         <button class="btn" style="border-radius : 0px !important; background-color:#fd7e14; color: white;"  v-on:click="editAvailability(product.id,1);" v-if="product.statusLabel=='unavailable'"> <i class="fas fa-exchange-alt"></i> Set to Available</button>
                                                         <button class="btn" style="border-radius : 0px !important; background-color:#fd7e14; color: white;" v-on:click="editAvailability(product.id,0);" v-else><i class="fas fa-exchange-alt"></i> Set to Unavailable</button>
                                                         <button class="btn" style="border-radius : 0px !important; background-color:#fd7e14; color: white;" v-on:click="launchRemoveProductModal(product.id)"><i class="fas fa-trash"></i> Remove Product</button>
                                                  </div>
                                              </div>
                                           </div>
                                       </div>
                                         <div class="row mt-2 justify-content-center" v-if="products.length != 0 && productSearchName == ''">
                                             <div class="col-5">
                                               <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchProductsPagination(productsPagination.prev_page_url)" :disabled="!productsPagination.prev_page_url"> <i class="fas fa-chevron-circle-left"> </i> Prev</button>
                                             </div>
                                             <div class="col-2">
                                               <span> page {{productsPagination.current_page}} of {{productsPagination.last_page}}</span>
                                             </div>
                                             <div class="col-5">
                                                  <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchProductsPagination(productsPagination.next_page_url)" :disabled="!productsPagination.next_page_url"> <i class="fas fa-chevron-circle-right"> </i> Next</button>
                                             </div>
                                        </div>

                                        <div class="card mt-3" v-if="products.length == 0 || productFilter.length == 0" >
                                           <div class="card-body">
                                               <div class="row ml-3 text-center">
                                                  <div class="col-12 text-center"> <h5> No Products yet, try adding one. </h5></div>
                                               </div>
                                            </div>
                                       </div>
                                    </div>
                                    <div class="card" v-else>
                                        <div class="card-body text-center">
                                           <p> Please wait for your request to be accepted. </p>
                                        </div>
                                    </div>
                                </div>
                    </div>

                                <!-- Product Categoryies-->
                    <div class="tab-pane fade" id="v-pills-categories" role="tabpanel" aria-labelledby="v-pills-categories-tab">
                               <div class="card">
                                    <div class="card-header">
                                       <span style="font-size:16px;"><i class="fas fa-list-ul"> </i> My Product Categories </span>
                                    </div>
                                    <div class="card-body" v-if="storeAccess.length != 0">
                                        <div class="row">
                                            <div class="col-7">
                                                <input class="form-control" placeholder="Search Here..." v-model="categorySearchName">
                                            </div>
                                            <div class="col">
                                                <button class="btn btn-success" style="border-radius : 0px !important;background-color : #fd7e14;"><i class="fas fa-search"> </i> Search </button>
                                                <button class="btn btn-success" style="border-radius : 0px !important;background-color : #fd7e14;" v-on:click="launchCategoryModal();"> <i class="fas fa-plus-circle"> </i> Add Category </button>
                                                <button class="btn btn-success" style="border-radius : 0px !important;background-color : #fd7e14;" v-on:click="categoryRequestModal();"><i class="fas fa-concierge-bell"> </i> Request a Category </button>
                                            </div>

                                        </div>
                                            <div style="margin-top:10px;"><span style="font-size:18px;" class="ml-3 mb-5"> List of categories</span>
                                            </div>

                                             <div class="card mt-3" v-for="category in categories" v-if="showLoading == false && categories.length != 0">
                                                  <div class="card-body">
                                                      <div class="row">
                                                          <div class="col">
                                                            <span style="font-size:16px;">{{category.category}} </span>
                                                          </div>
                                                          <div class="col-4">
                                                             <button class="btn" style="font-size: 16px; border-radius : 0px !important; background-color: #fd7e14; color: white;" v-on:click="showRemovePrompt(category.id)"><i class="fas fa-trash"> </i> Remove Category </button>
                                                          </div>
                                                      </div>

                                                  </div>
                                             </div>
                                             <div class="row mt-2 justify-content-center" v-if="categories.length != 0 && categorySearchName == ''">
                                                <div class="col-5">
                                                   <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchProductCategoryPaginate(categoryPagination.prev_page_url)" :disabled="!categoryPagination.prev_page_url"> <i class="fas fa-chevron-circle-left"> </i> Prev</button>
                                               </div>
                                               <div class="col-2">
                                                   <span> page {{categoryPagination.current_page}} of {{categoryPagination.last_page}}</span>
                                               </div>
                                               <div class="col-5">
                                                   <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchProductCategoryPaginate(categoryPagination.next_page_url)" :disabled="!categoryPagination.next_page_url"> <i class="fas fa-chevron-circle-right"> </i> Next</button>
                                                </div>
                                             </div>
                                             <div class="card mt-3" v-if="categories.length == 0">
                                                  <div class="card-body">
                                                      <div class="row">
                                                          <div class="col text-center">
                                                            <h5> Please set some categories that your store offers.</h5>
                                                          </div>
                                                      </div>

                                                  </div>
                                             </div>
                                          <div class="progress" v-if="showLoading == true">
                                                 <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; height : 300px;"></div>
                                         </div>
                                    </div>
                                    <div class="modal-body" v-else>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <p> Please wait for your request to be accepted. </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    </div>
                                  <!-- My Notifications-->
                    <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab">
                                     <div class="card">
                                       <div class="card-header">
                                        <span style="font-size:16px;"><i class="fas fa-bell"> </i>  My Notifications </span>
                                    </div>
                                    <div class="card-body">
                                          <div class="row mb-2" v-if="notifications.length != 0">
                                               <div class="col"> <strong> Request Type </strong></div>
                                               <div class="col"> <strong> Request Status</strong></div>
                                               <div class="col"> <strong> Accomodated By</strong></div>
                                               <div class="col"> <strong> Accomodated At</strong></div>
                                           </div>
                                           <div class="row mt-2 border-bottom" v-for="notification in notifications" v-if="notifications.length != 0">
                                               <div class="col"> <p> {{notification.type}} </p> </div>
                                               <div class="col" v-if="notification.type=='Concern'"> <button @click="viewAcceptedCategoryConcern(notification.categoryrequest, notification.type)" class="btn btn-success" style="border-radius:0px;"> {{notification.status}} </button> </div>
                                               <div class="col" v-else-if="notification.status=='Accepted'"> <button class="btn btn-success" disabled style="border-radius:0px; cursor: no-drop;"> {{notification.status}} </button> </div>
                                               <div class="col" v-else-if="notification.status=='with Concern'"> <button class="btn btn-danger" style="border-radius:0px; cursor: no-drop;"> Rejected </button> </div>
                                               <div class="col"> <p> {{notification.accomodatedBy}} </p> </div>
                                               <div class="col"> <p> {{notification.dateaccomodated}} </p> </div>
                                           </div>
                                           <div class="row mt-2 justify-content-center" v-if="notificationPagination.total != 0 && categorySearchName == ''">
                                                <div class="col-5">
                                                   <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchNotificationPaginate(notificationPagination.prev_page_url)" :disabled="!notificationPagination.prev_page_url"> <i class="fas fa-chevron-circle-left"> </i> Prev</button>
                                               </div>
                                               <div class="col-2">
                                                   <span> page {{notificationPagination.current_page}} of {{notificationPagination.last_page}}</span>
                                               </div>
                                               <div class="col-5">
                                                   <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchNotificationPaginate(notificationPagination.next_page_url)" :disabled="!notificationPagination.next_page_url"> <i class="fas fa-chevron-circle-right"> </i> Next</button>
                                                </div>
                                             </div>

                                           <div class="row" v-if="notifications.length == 0">
                                               <div class="col-12 text-center">
                                                     <h5>  No notifications yet. </h5>
                                               </div>
                                           </div>

                                    </div>
                                </div>
                    </div>
                                <!--My Settings -->
                    <div class="tab-pane fade " id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <div class="card">
                                    <div class="card-header">
                                       <span style="font-size:16x;"> <i class="fas fa-cog"> </i> My Settings </span>
                                    </div>
                                    <div class="card-body">
                                        <div id="accordion">
                                            <div class="card">
                                                <div class="card-header" id="headingOne" style="cursor:pointer;"  data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseTwo">
                                                           <span class="mb-0" style="font-size:16px;"> <i class="fas fa-credit-card" style="margin-right:10px;"> </i> Setup payment terms</span>
                                                </div>
                                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                                 <div class="card-body" v-if="storeAccess.length != 0">
                                                    <div class="row">
                                                        <div class="col-6" v-if="showLoading == false">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="CUP" v-model="paymentTermOffered.isCUP">
                                                                <label class="custom-control-label" for="CUP">Cash Upon Pickup</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="COD" v-model="paymentTermOffered.isCOD">
                                                                <label class="custom-control-label" for="COD">Cash on Delivery</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <button class="btn" style="background-color:#fd7e14;border-radius:0px;color:white;" v-on:click="savePaymentTerm();"> <i class="fas fa-save"> </i> Save Changes </button>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 50px;" v-if="paymentTermOffered.isCOD == true">
                                                        <div class="col-8"> <input class="form-control" placeholder="Delivery note..." v-model="deliveryNote"> </div>
                                                        <div class="col-4"> <button class="btn" style="background-color:#fd7e14;border-radius:0px;color:white;" @click='saveDeliveryNote()'> <i class="fas fa-save"> </i> Save Changes </button>  </div>
                                                    </div>
                                        </div>
                                   </div>
                                   </div>
  </div>
  <div class="card mt-3">
    <div class="card-header" id="headingTwo" style="cursor:pointer;"  data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
      <span class="mb-0" style="font-size:16px;"><i class="fas fa-lock" style="margin-right:10px;"> </i>  Change Password</span>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
          <div class="row">
              <div class="col-4">
                  <div class="form-group">
                      <label class="form-label"> Current Password :</label>
                      <input type="password" class="form-control">
                  </div>
                  <div class="form-group">
                      <label class="form-label"> New Password :</label>
                      <input type="password" class="form-control">
                  </div>
                  <div class="form-group">
                      <label class="form-label"> Confirm Password :</label>
                      <input type="password" class="form-control">
                  </div>
                  <hr>
                  <div class="form-group">
                      <button class="btn form-control" style="border-radius:0px; background-color:#fd7e14; color: white;"> <i class="fas fa-save"> </i> Save Changes </button>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>

</div>
</div>
</div>
  </div>
    </div>

    </div>
     </div>

<myFooter></myFooter>
<!-- Category Request Modal -->
<div class="modal fade" id="categoryRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header"> <h5> Request a category </h5></div>
         <div class="modal-body">
            <div class="card" v-if="iscategorysended == false">
                <div class="card-body p-2">
                     <div class="row">
                       <div class="col-12">
                          <input type="text" class="form-control" placeholder="Enter the category you want to request here.." v-model="categoryrequested">
                       </div>
                     </div>
                     <div class="row mt-2">
                        <div class="col-6">
                           <button type="button" class="btn form-control" style="border-radius: 0px; background-color: rgb(253, 126, 20); color: white; border-radius: 0px;" @click="sendCategoryRequest()"> <i class="fas fa-paper-plane"> </i> Send Request </button>
                        </div>
                        <div class="col-6">
                           <button type="button" class="btn form-control" style="border-radius: 0px; background-color: rgb(253, 126, 20); color: white; border-radius: 0px;" data-dismiss="modal" ><i class="fas fa-times-circle"> </i> Cancel </button>
                        </div>
                     </div>
                </div>
            </div>
            <div class="card" v-if="iscategorysended == true">
                <div class="card-body">
                <p class="text-center"> Sending your request, Please wait a moment.. </p>
                </div>
            </div>
         </div>
    </div>
  </div>
</div>
<!-- Manage Order -->
<div class="modal fade" id="ManageOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
         <div class="modal-body">
             <div class="card" v-for="single in singleOrder">
                 <div class="card-header">
                     <div class="row">
                         <div class="col-12">
                             <span style="text-transform: capitalize;"> {{single.customerName}} </span>
                         </div>
                     </div>
                 </div>
                 <div class="card-body">
                     <div class="row">
                         <div class="col-5">
                            <p class="form-label"> {{single.paymentterm}} </p>
                         </div>
                         <div class="col-7">
                             <select class="form-control" style="border-radius: 0px;" v-if="single.paymenttermid == 1" v-model="manageOrderCUPstatus">
                                 <option :value="3"> Fetched </option>
                                 <option :value="4"> Cancelled </option>
                             </select>
                             <select class="form-control" v-if="single.paymenttermid == 2" v-model="manageOrderCODstatus">
                                 <option :value="2"> Delivered </option>
                                 <option :value="4"> Cancelled </option>
                             </select>
                         </div>
                     </div>
                     <div class="row mt-2 border-top" v-if="manageOrderCODstatus == 2 && single.paymenttermid == 2">
                         <div class="col-3 mt-3">
                           <p> Reason :</p>
                         </div>
                         <div class="col-9 mt-3">
                             <select class="form-control" style="border-radius: 0px;font-size: 13px;" v-model="manageOrderCODcancelstatus">
                                 <option :value="1"> The customer did not show up during arrival. </option>
                                 <option :value="2"> Either the delivery truck or the delivery personel is not available. </option>
                             </select>
                         </div>
                     </div>
                     <div class="row" v-if="manageOrderCODcancelstatus == 1 && manageOrderCODstatus == 2 && single.paymenttermid == 2 ">
                         <div class="col">
                             <p> Cancelling this order with this concern will make this customer ban</p>
                         </div>
                    </div>

                     <div class="row mt-3">
                        <div class="col-6"><button class="btn form-control" data-dismiss="modal" style="border-radius: 0px;color: white; background-color: #fd7e14;" v-on:click="saveOrderChanges(single.orderId,single.paymenttermid)"><i class="fas fa-save"> </i> Save </button> </div>
                         <div class="col-6"> <button class="btn form-control" data-dismiss="modal" style="border-radius: 0px;color: white; background-color: #fd7e14;"> <i class="fas fa-times-circle"> </i> Close </button> </div>
                     </div>
                 </div>
             </div>
         </div>
    </div>
  </div>
</div>
<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Do you wish to Logout?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
           <div class="modal-footer justify-content-center">
         <button type="button" class="btn btn-danger" v-on:click="Logout()">Yes</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<!-- Remove Category Modal -->
<div class="modal fade" id="removeCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Do you wish to remove this category?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
             <div class="justify-content-center text-center" v-if="storeCurrentProductCategory.length == 0">
                  <button class="btn btn-danger" v-on:click="removeCategory()"> Remove </button>
                  <button class="btn btn-success" data-dismiss="modal"> Cancel </button> <br>
                  <small> Note: Removing this category may affect its products</small>
              </div>
                <div class="justify-content-center text-center" v-else>
                  <p> There are Products that are registered in this categories. <br> Remove the products under in this category first before removing this category</p>
              </div>
      </div>

    </div>
  </div>
</div>
<!-- Product Category Modal -->
<div class="modal fade" id="productCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add a Product Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-3 ">
          <div class="form-group">
              <label> Product Category </label>
              <select class="form-control" v-model="selectedCategory">
                  <option v-for="productCategory in productCategories" v-bind:value="productCategory.id"> {{productCategory.category}} </option>
              </select>
              <small> Select a category of products you want to offer. </small>
             </div>
             <div class="row">
                 <div class="col-6">
         <button type="button" class="btn form-control" style="border-radius: 0px; background-color: #fd7e14; color: white;" v-on:click="addProductCategory()"> <i class="fas fa-plus-circle"> </i> Add</button>
                 </div>
                 <div class="col-6">

        <button type="button" class="btn form-control" style="border-radius: 0px; background-color: #fd7e14; color: white;"  data-dismiss="modal"> <i class="fas fa-times-circle"> </i> Cancel</button>
                 </div>
            </div>
      </div>
    </div>
  </div>
</div>
<!-- Adding Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-box"> </i>  Add a Product </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-3 ">
          <form enctype="multipart/form-data">
        <div class="form-group">

            <img class="preview" v-if="hasSelectedImage == false" :src="'/uploads/productImages/noimage.jpeg'">
           <img class="preview" v-else :src="imageData">
        </div>
        <div class="form-group">

           <input class="form-control" type="file" accept="image/x-png,image/gif,image/jpeg" @change="previewProductImage">
        </div>
          <div class="form-group">
              <label> Product Category :</label>
              <select class="form-control" v-model="product_add.category">
                  <option v-for="category in categories" v-bind:value="category.id"> {{category.category}} </option>
              </select>
             </div>
             <div class="form-group">
                 <label> Product Name : </label>
                 <input class="form-control" type="text" v-model="product_add.name">
             </div>
              <div class="form-group">
                 <label> Product Description : </label>
                 <input class="form-control" type="text" v-model="product_add.description">
             </div>
             <label> Product Price : </label>
               <div class="form-check">
                  <input type="checkbox" class="form-check-input" v-model="priceNego">
                  <label class="form-check-label"> Negotiable </label>
               </div>
              <div class="form-group" v-if="priceNego == false">
                  <div class="input-group mb-2">
                     <div class="input-group-prepend">
                         <div class="input-group-text"> ₱ </div>
                     </div>
                         <input type="text" class="form-control" id="inlineFormInputGroup" v-model="product_add.price">
                  </div>
             </div>
             </form>
             <div class="row">
                 <div class="col-6">
         <button type="button" class="btn form-control" style="border-radius: 0px; background-color: #fd7e14; color: white;" v-on:click="registerProduct()"> <i class="fas fa-plus-circle"> </i> Add </button>
                 </div>
                 <div class="col-6">
        <button type="button" class="btn form-control" style="border-radius: 0px; background-color: #fd7e14; color: white;" data-dismiss="modal"> <i class="fas fa-times-circle"> </i> Cancel</button>
                 </div>
             </div>
     </div>
    </div>
  </div>
</div>
<!-- Edit Product Modal -->
<div class="modal fade" id="editproductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <div class="form-group">
           <img class="preview"  :src="'/uploads/productImages/'+product_edit.image" v-if="hasSelectedImage == false">
               <img class="preview" :src="imageData" v-else>
        </div>
        <div class="form-group">
           <input class="form-control"  type="file" accept="image/x-png,image/gif,image/jpeg" @change="previewProductImage">
        </div>
      <div class="modal-body p-3 ">
            <ul class="alert alert-danger" v-for="errors in edit_errors" v-if="errors.toString() != '1'">
                <li> {{errors.toString()}} </li>
            </ul>
          <div class="form-group">
              <label> Product Category :</label>
              <select class="form-control" v-model="product_edit.category">
                  <option v-for="category in categories" v-bind:value="category.id"> {{category.category}} </option>
              </select>
             </div>
             <div class="form-group">
                 <label> Product Name : </label>
                 <input class="form-control" type="text" v-model="product_edit.name">
             </div>
              <div class="form-group">
                 <label> Product Description : </label>
                 <input class="form-control" type="text" v-model="product_edit.description">
             </div>
                 <label> Product Price : </label>
             <div class="form-check">
                  <input type="checkbox" class="form-check-input" v-model="priceNegoEdit">
                  <label class="form-check-label"> Negotiable </label>
               </div>
              <div class="form-group" v-if="priceNegoEdit == false">
                 <input class="form-control" type="number" v-model="product_edit.price">
             </div>
       </div>
          <div class="modal-footer justify-content-center" v-if="showLoading == false">
         <button type="button" class="btn btn-success btn-edit " v-on:click="updateProduct()">Save Changes</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
        <div class="progress" v-else>
              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
         </div>

    </div>
  </div>
</div>
<!-- Expiry Alert Modal -->
<div class="modal fade" id="ExpireModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Your access is expire, try subscribing for 30 days</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
          <div class="modal-footer justify-content-center">
         <button type="button" class="btn btn-success" >Send Subscription Request</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- Remove Product Modal -->
<div class="modal fade" id="removeProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Do you wish to remove this product ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
             <div class="justify-content-center text-center">
                  <button class="btn btn-danger" style="background-color: #fd7e14; border-radius:0px;" v-on:click="removeProduct()"> Remove </button>
                  <button class="btn btn-success" style="background-color: #fd7e14; border-radius:0px;" data-dismiss="modal"> Cancel </button> <br>
                  <small> Note: Removing a product will not be recovered, think wisely</small>
              </div>

      </div>

    </div>
  </div>
</div>
<!-- Notifications Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Notification </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-3 ">
           <form>
               <div class="form-group">
                   <label class="form-label">Request Type : </label>
                   <input class="form-control" disabled v-model="viewRequestNotif.type">
               </div>
                <div class="form-group">
                   <label class="form-label">Request Status : </label>
                   <input class="form-control" disabled v-model="viewRequestNotif.status">
               </div>
               <div class="form-group">
                   <label class="form-label">Accomodated By : </label>
                   <input class="form-control" disabled v-model="viewRequestNotif.accomodatedBy">
               </div>
               <div class="form-group">
                   <label class="form-label">Accomodated At : </label>
                   <input class="form-control" disabled v-model="viewRequestNotif.dateaccomodated">
               </div>

           </form>
     </div>
          <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Delete </button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- View Concern Modal-->
<div class="modal fade" id="viewConcernModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                       <div class="col-12"> <p class="text-justify" style="font-size: 20px;">
                         <span v-if="singleNotificationStatus == 'Concern'"> Sorry, </span>
                         <span v-else>   Congratualations, </span>
                         your requested category of <strong style="text-transform: capitalize;"> {{acceptedcategoryname}} </strong> has been
                       <span v-if="singleNotificationStatus == 'Concern'" style="font-weight: bold;"> Rejected  </span>
                       <span v-else style="font-weight: bold;">   Accepted.  </span>
                         </p> </div>
                    </div>
                </div>
            </div>

        </div>
           <div class="modal-footer justify-content-center">
               <div class="row">
                   <div class="col-12">
                       <button type="button" class="btn btn-success" data-dismiss="modal">Got It</button>
                   </div>
               </div>
      </div>
    </div>
  </div>
</div>
<!-- Send Message Modal-->
<div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
       <div class="modal-header"> <span> Compose a message </span>
       </div>
        <div class="modal-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                       <div class="col-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <textarea style="resize:none; border-radius:0px;" class="form-control" placeholder="Type a message..." v-model="customerMessageBody"> </textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                             <button class="btn form-control" style="border-radius:0px;background-color:#fd7e14; color:white;" @click="sendMessageToCustomerFromOrder()"> <i class="fas fa-paper-plane"> </i> Send Message </button>
                        </div>
                        <div class="col-6">
                             <button class="btn form-control" style="border-radius:0px;background-color:#fd7e14; color:white;" data-dismiss="modal"> <i class="fas fa-times-circle"> </i> Close </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<div id="MessageButtonContainer" v-if="isMessageClicked == false">
    <button class="btn " style="border-radius: 0px; background-color: #fd7e14; color:white;" @click="viewMessageThreads()"> <span style="font-size:20px;"> <i class="fas fa-envelope"> </i> Messages </span> <span v-if="unSeenMsg != 0" class="badge badge-danger"> {{unSeenMsg}} </span> </button>
</div>
<div id="MessageThreadContainer" v-if="isMessageClicked == true">
    <div class="card w-100">
      <div class="card-header">
          <div class="row">
              <div class="col-9"><i class="fas fa-envelope"> </i>  Messages</div>
              <div class="col-3 justify-content-end"><button type="button" @click="isMessageClicked=false;" class="close"  aria-label="Close"><span aria-hidden="true" style="color:white;">&times;</span></button>  </div>
              </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                   <div class="card">
                       <div class="card-header"> <span style="font-size:16px;"> <i class="fas fa-user"> </i> Customer  </span> </div>
                       <div class="card-body" style="overflow-y:scroll; height: 400px; padding : 5px 5px 5px 5px;">
                           <ul class="list-group" v-if="messageHeads.length != 0">
                               <li class="list-group-item mt-2" v-for="messageHead in messageHeads" style="text-transform:capitalize; cursor:pointer;" @click="viewConvo(messageHead.msgId, messageHead.customerId)"> <img :src="'/uploads/productImages/'+messageHead.profilepic" style="height:25px; width: 25px; border-radius:40px;">
                                   <span v-if="messageHead.isSeen == 1 "> {{messageHead.firstname}} {{messageHead.lastname}}  </span>
                                   <strong v-else> {{messageHead.firstname}} {{messageHead.lastname}}  </strong>
                                 </li>
                           </ul>
                           <ul class="list-group" v-if="messageHeads.length == 0">
                               <li class="list-group-item text-center"> No messages </li>
                           </ul>
                       </div>
                   </div>
                </div>
                <div class="col-7" v-if="messageHeads.length != 0">
                     <div class="card">
                       <div class="card-header"> <span style="font-size:16px;"> <i class="fas fa-comments"> </i> Conversation  </span> </div>
                       <div class="card-body" style="overflow-y:scroll; height: 400px; padding : 5px 5px 5px 5px;">
                         <ul class="list-group" v-for="message in messages">
                            <div class="list-group-item mt-2"  v-if="message.isFromStore == 0" style="padding : 5px 5px 5px 15px;">
                                 <span> {{message.message}}  </span>
                            </div>
                            <div  class="list-group-item mt-2 text-right" style="background-color:#fd7e14; color:white; padding : 5px 5px 5px 15px;" v-if="message.isFromStore == 1">
                                <span> {{message.message}} </span>
                            </div>
                         </ul>
                       </div>
                       <div class="row p-2" v-if="isConvoClick == true">
                           <div class="col-sm-9">
                             <textarea v-model="messageBody" @click="localisSeen = 0;" style="resize:none; border-radius: 0px;" class="form-control" placeholder="Type a message..."> </textarea>
                           </div>
                           <div class="col-sm-3">
                               <span style="color:#fd7e14; font-weight:bold;cursor:pointer;" @click="sendMessage()"> <i class="fas fa-paper-plane"> </i> Send </span>
                           </div>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</template>
<script>
var map;
var marker;
import myFooter from './myFooter';
 import myHeader from './myHeader';
 import GoogleMap from  './GoogleMap';
 import Loading from './Loading';
export default {
    components :{
        'myFooter' : myFooter,
         'myHeader' : myHeader,
         'google-map' : GoogleMap,
         'Loading' : Loading
    },
  data: function() {
    return {
      storeProfile: [],
      storeId: localStorage.getItem("id"),
      productCategories: [],
      selectedCategory: 0,
      categories: [],
      categoryPagination : [],
      ordersPagination : [],
      orders : [],
      category_id: 0,
      product_category: 0,
      product_add: {
        category: 0,
        name: "",
        description: "",
        price: 1,
        image: new Image()
      },
      products: [],
      productsPagination : [],
      product_id : 0,
      paymentTerm : {
          COD : false,
          pickup : false
      },
      storeAccess : [],
      showLoading : false,
      showAvailabilityLoading : false,
      storeCurrentProductCategory : [],
      notifications : [],
      notificationPagination : [],
      product_edit: {
          id : 0,
          name : '',
          category : 0,
          description : '',
          price : 0,
          image : ''
      },
      edit_errors :[],
      edit_hasError : false,
      imageData : "",
      profilePictureData : '',
      isEmailVerified : true,
      product_add_errors : [],
      hasSelectedImage : false,
      profilepicture_error : [],
      profile_picture : '/uploads/productImages/noprofilepic.jpg',
      hasSelectedProfilePicture : false,
      paymentTermOffered : {
        isCOD : false,
        isCUP : false
      },
      isStoreHidden : false,
      hasSendSubscriptionRequest : [],
      RequestNotifCount : 0,
      viewRequestNotif : {
        id : 0,
        accomodatedBy : '',
        status : '',
        dateaccomodated : '',
        type : '',

      },
        orderNotifCount : 0,
        ordersPagination: [],
        orderItems : [],
        singleOrder : [],
        manageOrderCODstatus : 2,
        manageOrderCUPstatus : 3,
        manageOrderCODcancelstatus: 1,
        orderFilterTerm: 1,
        orderFilterCODStatus: 1,
        orderFilterCUPStatus: 1,
        editProductImageisClicked : false,
        categoryrequested: '',
        iscategorysended : false,
        isMessageClicked : false,
        acceptedcategoryname : '',
        messageHeads : [],
        messages : [],
        isConvoClick : false,
        MsgId : 0,
        messageBody : '',
        customerIdMessage : '',
        customerMessageBody : '',
        selectedMsgHead: 0,
        FilterDueDate : new Date,
        orderSearchCustomer : '',
        dateDeliver : '',
        DateSortKey : 'desc',
        top3Buyers : [],
        top3Products : [],
        deliveryNote : '',
        priceNego : false,
        priceNegoEdit : false,
        messageCustomerId : 0,
        productSearchName : '',
        categoryRequestisSent : false,
        categorySearchName : '',
        unSeenMsg : 0,
        localisSeen : 0,
        singleNotificationStatus  : ""
    };
  },
  computed: {
        orderFilterCustomerName :function(){
          return this.orders.filter( order => {
            return order.customerName.toLowerCase().includes(this.orderSearchCustomer.toLowerCase());
          });
        },
        productFilter : function(){
            return this.products.filter( product => {
                return product.name.toLowerCase().includes(this.productSearchName.toLowerCase());
            })
        }
  },
  watch : {

    DateSortKey : function(val){
        let data = {storeId : this.storeId , orderKey : val};
        this.showLoading = true;
          axios.post('api/filterOrdersByDueDate',data).then(response => {
              this.showLoading = false;
            this.orders = response.data;
          });
    },
    orderFilterTerm : function(val){
        if (val == 2){
            let data = {storeId : this.storeId ,term : 2, status : this.orderFilterCODStatus};
           axios.post('api/filterOrder',data).then(response=>{
             this.orders = response.data;
           });
        }
        if(val == 1){
            let data = {storeId : this.storeId ,term : 1, status : this.orderFilterCUPStatus};
           axios.post('api/filterOrder',data).then(response=>{
             this.orders = response.data;
           });
        }
        if(val == 3){
           this.fetchAllAOrders();
        }
    },
    orderFilterCODStatus : function(val){
      let data = {storeId : this.storeId, term : this.orderFilterTerm , status : val};
           axios.post('api/filterOrder',data).then(response=>{
             this.orders = response.data;
           });
    },
    orderFilterCUPStatus : function(val){
        let data = {storeId : this.storeId, term : this.orderFilterTerm , status : val};
           axios.post('api/filterOrder',data).then(response=>{
             this.orders = response.data;
        });
    }
  },
  // ang save payment terms wala pay method !!
  methods: {
        storeUnseenMsg : function(){
        let data = {storeId : this.storeId};
            axios.post('api/storeUnseenMsg',data).then(resp => {
            this.unSeenMsg = resp.data.length;
            if(this.unSeenMsg != 0){
                this.localisSeen = 0;
            }
            });
        },
    redirectToorders : function(){
      $('#v-pills-home-tab').tab('show');
    },
    redirectToProducts : function(){
       $('#v-pills-products-tab').tab('show');
    },
    redirectToProductCategories : function(){
       $('#v-pills-categories-tab').tab('show');
    },
    saveDeliveryNote : function(){
          this.showLoading = true;
            let data = {storeId: this.storeId, note: this.deliveryNote};
           axios.post('api/saveDeliveryNote',data).then(resp => {
              this.showLoading = false;
              this.loadPaymentTerms();
           });

    },
    viewSingleOrder : function(orderId){
         localStorage.setItem('orderId',orderId);
         this.$router.push({ name : 'StoreSingleOrder'});
      },
      listenToUnseenMessage : function(){
           Echo.channel('ChatUnseenChannel').listen('ChatUnseenEvent', message=>{
             console.log(message);

              if(message.storeId == this.storeId){
                  var audio = new Audio('http://soundbible.com/mp3/AirPlaneDing-SoundBible.com-496729130.mp3');
             audio.play();
                     this.ViewUpdatedConvo();
               }
       });
    },
    listenToStoreMessage : function(){
        Echo.channel('listenToCustomerChannel').listen('ListenToCustomerMsg', message=>{
             console.log(message);
              if(message.storeId == this.storeId){
                     this.ViewUpdatedConvo();

               }
       });
    },
    sendMessageToCustomerFromOrder : function(){
        let data = {storeId : this.storeId , customerId : this.customerIdMessage, message: this.customerMessageBody};
        axios.post('api/sendMessageToCustomerFromOrder',data).then(response=>{
          $('#sendMessageModal').modal('hide');
        });
    },
    launchSendMessage: function(customerId){
          this.customerIdMessage = customerId;
         $('#sendMessageModal').modal('show');
    },
    sendMessage : function(){
       let data = {msgId: this.MsgId, message : this.messageBody, customerId : this.messageCustomerId};
        axios.post('api/sendMessageToCustomer',data).then(response=>{
           this.messageBody = "";
           this.ViewUpdatedConvo();
        });
    },
    ViewUpdatedConvo : function(){
        this.isConvoClick = true;

       let data = {msgId : this.MsgId, isSeen : 1 };
       axios.post('api/storeViewConvo',data).then(response=>{
         this.messages = response.data;
       });
           this.storeUnseenMsg();
    },
    viewConvo : function(msgId, customerId){
        this.isConvoClick = true;

       let data = {msgId : msgId};
       this.messageCustomerId = customerId;
       this.localisSeen = 1;
       axios.post('api/storeViewConvo',data).then(response=>{
         this.MsgId = response.data[0].msgId;
         console.log(this.MsgId);
         this.messages = response.data;

         setTimeout( ()=>{
             this.fetchMessageHeads();
             this.storeUnseenMsg();
         },2000)
       });
    },
    fetchMessageHeads : function(){
        let data = {storeId : this.storeId};
        axios.post('api/storeGetMessageHeads',data).then(response=>{
           this.messageHeads = response.data;

           setTimeout( ()=>{
             this.storeUnseenMsg();
         },2000)
        });
    },
    viewAcceptedCategoryConcern: function(concern, status){
          this.acceptedcategoryname = concern;
          this.singleNotificationStatus = status;
          $('#viewConcernModal').modal('show');
    },
    viewMessageThreads : function(){
      this.isMessageClicked = true;
      this.fetchMessageHeads();
    },
    categoryRequestModal : function(){
        $('#categoryRequestModal').modal('show');
        this.iscategorysended =false;
    },
    sendCategoryRequest : function(){
       let data = {storeId : this.storeId, status : 2,category : this.categoryrequested, type : 3};
         this.categoryRequestisSent = true;
         axios.post('api/sendCategoryRequest',data).then(response=>{
           this.iscategorysended = true;
           $('#categoryRequestModal').modal('hide');
           setTimeout( ()=>{
              this.categoryRequestisSent = false;
           },3000);
         });
    },
    saveOrderChanges : function(orderId,paymentterm){
      // for cancelling the order
       if(this.manageOrderCODstatus == 2 || this.manageOrderCUPstatus == 2){
           let data = {orderId : orderId, cancellType : this.manageOrderCODcancelstatus};
            axios.post('api/cancellCustomerOrder',data).then(response=>{

              $('#ManageOrderModal').modal('hide');
              this.showLoading = true;
              setTimeout(()=>{
                 this.fetchAllAOrders();
                 this.showLoading = false;
              },600);
            }, error=>{
              $('#ManageOrderModal').modal('hide');
              this.showLoading = true;
               setTimeout(()=>{
                 this.fetchAllAOrders();
                 this.showLoading = false;
              },600);
            });

       }// for COD accepted
       else if(paymentterm == 2){
         this.showLoading = true;
        let data = {orderId : orderId , status : this.manageOrderCODstatus};
          axios.post('api/acceptCustomerOrder',data).then(response=>{
            setTimeout(()=>{
                 this.fetchAllAOrders();
                 this.showLoading = false;
              },600);
          });
       }// for CUP accepted
       else if(paymentterm == 1){
         this.showLoading = true;
        let data = {orderId : orderId , status : this.manageOrderCUPstatus};
          axios.post('api/acceptCustomerOrder',data).then(response=>{
            setTimeout(()=>{
                 this.fetchAllAOrders();
                 this.showLoading = false;
              },600);
          });
       }

    },
    viewOrder: function(orderId){
       let data = {orderId : orderId};
        axios.post('api/viewSingleOrder',data).then(response=>{
           this.singleOrder = response.data;
       $('#ManageOrderModal').modal('show');
        });
    },
    fetchAllAOrders: function(){
      let data = {storeId: this.storeId}
        axios.post('api/getAllOrders',data).then(resp=>{
          this.orders = resp.data.data;
          this.makeOrdersPaginate(resp.data);
        });
    },
    makeOrdersPaginate : function(data){
       let pagination = {
           current_page: data.current_page,
           last_page : data.last_page,
           next_page_url : data.next_page_url,
           prev_page_url : data.prev_page_url
       }
       this.ordersPagination = pagination;
    },
    fetchOrdersPaginate : function(url){
        let data = {storeId : this.storeId};
       axios.post(url,data).then(resp => {
          this.orders = resp.data.data;
          this.makeOrdersPaginate(resp.data);
       });
    },
    fetchAllOrderItem : function(){
      let data = {storeId : this.storeId};
       axios.post('api/StoregetAllOrderItems',data).then(response=>{
            this.orderItems = response.data;
       });
    },
    listenToRequestNotification : function(){
           Echo.channel('requestNotifChannel').listen('StoreRequestNotificationEevent', message=>{
               if(message.storeId[0].userId == this.storeId){
                  this.RequestNotifCount += 1;
               }
       });
    },
    listenToOrderNotification: function(){
       Echo.channel('CustomerOrderChannel').listen('CustomerCheckOutEvent', message=>{
           this.getStoreIdFromCustomerOrder(message.cartId);
           this.orderNotifCount = this.orderNotifCount + 1;
       });
    },
    getStoreIdFromCustomerOrder: function(cartId){
      let data = {cartId : cartId};
       axios.post('api/getStoreIdFromCustomerOrder',data).then(response=>{
        if(response.data[0].storeId == this.storeId){
         this.orderNotifCount = this.orderNotifCount + 1;
        }
     });
    },
    hideStore : function(){
          let obj = {storeId : this.storeId};
        axios.post('api/hideStore',obj);
    },
    sendSubscriptionRequest : function(){
         let obj  = {userId : localStorage.getItem('id')};
        axios.post('api/sendSubscriptionRequest',obj);

        this.checkSendSubscriptionRequest();
    },
    checkSendSubscriptionRequest : function(){
       let obj = {storeId : this.storeId};
       axios.post('api/checkSendSubscriptionRequest',obj).then( (response)=>{
         this.hasSendSubscriptionRequest = response.data;

       });
    },
    saveProfilePicture : function(){
     var form = new FormData();
        let config = {
           'accept': 'application/json',
           'Accept-Language': 'en-US,en;q=0.8',
           'Content-Type':'multipart/form-data; boundary=${data._boundary}'
        }
     form.append('profilePicture',this.profilePictureData);
     form.append('storeId',this.storeId);
     axios.post('api/saveProfilePicture',form,config);
      setTimeout(()=>{
          this.loadProfilePicture();
      }),2000;
    },
    loadProfilePicture : function(){
      let obj = {storeId : this.storeId};
      axios.post('api/loadProfilePicture',obj).then((response)=>{
       this.profile_picture = response.data[0];
       console.log(this.profile_picture);
      });
    },
    preivewProfilePicture : function(event){
      var input = event.target;
      if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = (e) =>{

        this.hasSelectedProfilePicture = true;
        this.profilePictureData = input.files[0];
         this.profilePictureData = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    },
    previewProductImage : function(event){
      var input = event.target;
      if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = (e) =>{

        this.hasSelectedImage = true;
      this.product_add.image = input.files[0];
      this.product_edit.image = input.files[0];
          this.imageData = e.target.result;
          console.log(input.files[0]);
        }
        reader.readAsDataURL(input.files[0]);
      }

    },
    savePaymentTerm : function(){
      this.showLoading = true;
       if(this.paymentTermOffered.isCUP){
          let obj = {storeId : this.storeId,isOffered : 1};
          axios.post('api/CUPisOffered',obj).then(response=>{
            this.showLoading = false;
          });
       }else{
            let obj = {storeId : this.storeId,isOffered : 0};
          axios.post('api/CUPisOffered',obj).then(response =>{
            this.showLoading = false;
          });
       }

       if(this.paymentTermOffered.isCOD){
          let obj = {storeId : this.storeId,isOffered : 1};
          axios.post('api/CODisOffered',obj).then(response =>{
            this.showLoading = false;
          });
       }else{
          let obj = {storeId : this.storeId,isOffered : 0};
          axios.post('api/CODisOffered',obj).then(response =>{
            this.showLoading = false;
          });
       }

    },
    gotoOrders : function(){
     this.fetchAllAOrders();
     this.fetchAllOrderItem();
    },
    loadPaymentTerms : function(){
      let obj = {storeId : this.storeId};
      axios.post('api/loadPaymentTerms',obj).then(response=>{
         if(response.data[0].isOffered == 1){
             this.paymentTermOffered.isCUP = true;
         }else{
             this.paymentTermOffered.isCUP = false;
         }

         if(response.data[1].isOffered == 1){
              this.paymentTermOffered.isCOD = true;
              this.deliveryNote = response.data[1].note;
         }else{
           this.paymentTermOffered.isCOD = false;
            this.deliveryNote = response.data[1].note;
         }
      });
    },
    updateProduct : function(){
        let app = this;
        this.showLoading = true;
        let config = {
           'accept': 'application/json',
           'Accept-Language': 'en-US,en;q=0.8',
           'Content-Type':'multipart/form-data; boundary=${data._boundary}'
        }
        var form = new FormData();
          if(this.priceNegoEdit == true){
            form.append('price',0);
          }else{
              form.append('price', this.product_edit.price);
          }
        form.append('id',this.product_edit.id);
        form.append('name',this.product_edit.name);
        form.append('description',this.product_edit.description);
        form.append('isPictureSelected',this.hasSelectedImage);
        if(this.hasSelectedImage == true){
          form.append('image',this.product_edit.image);
        }else{
          form.append('image',null);
        }
        form.append('category',this.product_edit.category);

       axios.post('api/updateProduct',form,config).then(response=>{
         app.edit_errors = response.data;
         console.log('edit errors is' +app.edit_errors);
         if(app.edit_errors.length != 0){
           setTimeout(()=>{
            this.showLoading = false;
            $('#editproductModal').modal('hide');
            this.loadProducts();
         },2000);
         }
      });
    },
    launchViewNotifModal : function(id,accomodatedBy,status,date,type){
        this.viewRequestNotif.accomodatedBy = accomodatedBy;
        this.viewRequestNotif.status = status;
        this.viewRequestNotif.dateaccomodated = date;
        this.viewRequestNotif.type = type;
         this.viewRequestNotif.id = id;
      $('#notificationModal').modal('show');
    },
    loadNotifications : function(){
      let obj = {storeId : this.storeId};
      let app = this;
      axios.post('api/loadNotification',obj).then(response=>{
        app.notifications = response.data.data;
        this.makeNotificationPaginate(response.data);
      });
    },
    fetchNotificationPaginate : function (url){
     let data = {storeId : this.storeId};
      axios.post(url, data ).then(resp => {
        this.makeNotificationPaginate(resp.data);
        this.notifications = resp.data.data;
      })
    },
    makeNotificationPaginate : function (data){
     let pagination = {
        current_page : data.current_page,
        last_page : data.last_page,
        next_page_url : data.next_page_url,
        prev_page_url : data.prev_page_url
     }
      this.notificationPagination = pagination;
    },
    removeProduct : function(){
      let obj = {id : this.product_id};
      this.showLoading = true;
       axios.post('api/removeProduct',obj);
       this.showLoading = false;
     this.loadProducts();
     $('#removeProductModal').modal('hide');
    },
    launchRemoveProductModal : function(id){
      this.product_id = id;
      $('#removeProductModal').modal('show');
    },
    checkProductCategory : function(){
        let app = this;
        let obj = {categoryId : this.category_id, storeId  : this.storeId};
      axios.post('api/getProductThatHasCategory',obj).then(response=>{
       app.storeCurrentProductCategory = response.data;

      });
    },
    getStoreAccess : function(){
      let obj = {storeId : this.storeId};
      let app = this;

      axios.post('api/getStoreAccess',obj).then(response=>{
        app.storeAccess = response.data;
        if(response.data.length == 0){
        app.storeAccess = undefined;
        }else{
       console.log(app.storeAccess[app.storeAccess.length-1].daysLeft);
        if(app.storeAccess[app.storeAccess.length-1].daysLeft < 0){
             app.hideStore();
        }
        }

      });
    },
    savingPaymentTerm : function(){
        let obj = {storeId : this.storeId}
        let app = this;
          axios.post('api/addPaymentTerms',obj);
    },
    clickPickup : function(){
        this.paymentTerm.pickup = !this.paymentTerm.pickup;
      let isAdd = 0;
      this.paymentTerm.pickup == true ? isAdd = 1 : isAdd = 0;
      let obj = {isAdd : isAdd , storeId : this.storeId , term : 2 };
      axios.post('api/addPaymentTerms',obj);
    },
    clickCOD : function(){
      this.paymentTerm.COD = !this.paymentTerm.COD;
      let isAdd = 0;
      this.paymentTerm.COD == true ? isAdd = 1 : isAdd = 0;
      let obj = {isAdd : isAdd , storeId : this.storeId , term : 2 };
      axios.post('api/addPaymentTerms',obj);
    },
    Logout: function() {
      $("#logoutModal").modal("hide");
      localStorage.setItem('id',0);
      this.$router.push("/");
    },
    editAvailability: function(id, token) {
        console.log(id);
      let obj = { productId: id, token: token };
      this.showAvailabilityLoading = true;
      let app = this;

      axios.post("api/editAvailability", obj).then( ()=>{
          app.showAvailabilityLoading = false;
      });
      this.loadProducts();
    },
    viewSingleProduct: function(id) {
        this.hasSelectedImage = false;
      $("#editproductModal").modal("show");
       let obj = {productId : id};
       let app = this;
       axios.post('api/viewSingleProduct',obj).then(response=>{
         app.product_edit = response.data[0];
       });


    },
    registerProduct: function() {
      var form = new FormData();
      let app = this;
      let config = {
          'accept': 'application/json',
          'Accept-Language': 'en-US,en;q=0.8',
          'Content-Type':'multipart/form-data; boundary=${data._boundary}'
          }
       form.append('storeId',this.storeId);
       form.append('name',this.product_add.name);
       form.append('productCategory',this.product_add.category);
       if(this.priceNego == true){
       form.append('price',0);
       }else{
       form.append('price',this.product_add.price);
       }
       form.append('image',this.product_add.image);
       form.append('description',this.product_add.description);

       this.showLoading = true;
      axios.post("api/registerProduct", form,config).then((response)=>{
          app.product_add_errors = response.data;
            $("#productModal").modal("hide");
          setTimeout(()=>{
            this.loadProducts();
            this.showLoading = false;
          },2000);
      });

    },
    loadProducts: function() {
      let obj = { storeId: this.storeId };
       let app = this;
       this.showLoading = true;
       axios.post("api/loadProducts", obj).then(response => {
         app.products = response.data.data;
         this.makeProductsPaginate(response.data);
        app.showLoading = false;
      });
    },
    makeProductsPaginate : function(data){
        let pagination = {
            current_page : data.current_page,
            prev_page_url : data.prev_page_url,
            next_page_url : data.next_page_url,
            last_page : data.last_page,
        }
        this.productsPagination = pagination;
    },
    fetchProductsPagination : function(url){
      let data = {storeId  : this.storeId};
       axios.post(url,data).then(resp => {
         this.products = resp.data.data;
         this.makeProductsPaginate(resp.data);
       });
    },
    getStoreProfile: function() {
      let app = this;
      let obj = { storeId: this.storeId };

      axios.post("api/getStoreAccount", obj).then(response => {
        app.storeProfile = response.data;
      });
    },
    launchAddproductModal: function() {
        this.hasSelectedImage = false;

        $("#productModal").modal("show");

    },
    launchCategoryModal: function() {

        $("#productCategoryModal").modal("show");
        this.getProductCategories();

    },
    getProductCategories: function() {
      let app = this;
      let obj = { storeId: this.storeId };
      axios.post("api/getProductCategories", obj).then(response => {
        app.productCategories = response.data;

      });
    },
    fetchProductCategoryPaginate : function(url){
       let data = {storeId : this.storeId};
          axios.post(url,data).then(resp => {
            this.categories = resp.data.data;
            this.makeProductCategoryPaginate(resp.data);
          });
    },
    makeProductCategoryPaginate : function(data){
        let pagination = {
           current_page : data.current_page,
           last_page : data.last_page,
           next_page_url : data.next_page_url,
           prev_page_url : data.prev_page_url
        }
      this.categoryPagination = pagination;
    },
    addProductCategory: function() {
        this.showLoading = true;
      let obj = {
        storeId: this.storeId,
        productCategory: this.selectedCategory
      };
      axios.post("api/addProductCategory", obj);
      this.loadProductCategories();
        this.showLoading = true;
      $("#productCategoryModal").modal("hide");
    },
    loadProductCategories: function() {
      let obj = { storeId: this.storeId };
      let app = this;
      this.showLoading = true;
      axios.post("api/loadAllProductCategories", obj).then(response => {
        app.categories = response.data.data;
        this.makeProductCategoryPaginate(response.data);
        this.showLoading = false;
      });
    },
    showRemovePrompt: function(id) {
      $("#removeCategoryModal").modal("show");
       this.category_id = id;
       this.checkProductCategory();

    },
    removeCategory: function() {
      let obj = { storeId: this.storeId, categoryId: this.category_id };
      axios.post("api/removeProductCategory", obj);
      $("#removeCategoryModal").modal("hide");
      this.loadProductCategories();
    },
    gotoProducts : function(){
      this.loadProducts();
    },
    gotoSettings : function(){
     this.loadPaymentTerms();
    },
    gotoProductCategories : function(){
     this.loadProductCategories();
    },
    gotoNotifications : function(){
     this.loadNotifications();
    },
    getTop3Buyers : function(){
        let data = { storeId : this.storeId };
           axios.post('api/getTop3Buyers',data).then( resp =>{
             this.top3Buyers = resp.data;
           });
    },
    getTop3Products : function(){
        let data = { storeId : this.storeId };
         axios.post('api/getTop3Products',data).then(resp =>{
          this.top3Products = resp.data;
         });
    },
    listenToSingleOrder : function(){
       Echo.channel('singleOrderChannel').listen('SingleOrderBroadcaster', message=>{
              console.log(message.storeId == this.storeId);
              this.fetchAllAOrders();
              this.orderNotifCount = this.orderNotifCount + 1;
       });
    },
    checkEmailIsVerified : function(){
        let data = { 'customerId' : this.storeId};
        var arry = [];
         axios.post('api/CustomerisEmailVerified',data).then(resp => {
             arry = resp.data
             console.log(" is verified is "+arry[0].isVerified);
             if(arry[0].isVerified === 0){
                this.isEmailVerified = false;
                 this.$router.push({ name : 'StoreAccount'});
             }else if(arry[0].isVerified == 1){
                this.isEmailVerified = true;
             }
         });
    }
  },
  mounted: function() {
      this.checkEmailIsVerified();
      if(this.isEmailVerified == false){

      }else{
if(this.storeId == 0){
     this.$router.push({name : 'AdminPage'})
    }else{


     this.checkSendSubscriptionRequest();
     this.listenToRequestNotification();
     this.listenToOrderNotification();
     this.listenToStoreMessage();
     this.listenToSingleOrder();
           this.showLoading = true;
         this.loadProductCategories();
         this.loadProducts();
         this.fetchAllAOrders();

         setTimeout( ()=>{
          this.getStoreProfile();
          this.getStoreAccess();
          this.loadProfilePicture();

          setTimeout( ()=>{
            this.getTop3Buyers();
            this.getTop3Products();
               this.showLoading = false;

                setTimeout( ()=>{
                    this.storeUnseenMsg();
                });
          },3000);
         },3000);

    }
      }



  },
  created : function(){
    this.loadPaymentTerms();
  }
};
</script>
<style>
#dashboardCard,
#v-pills-tabContent {
  margin-top: 30px;
}

#Storeprofile {
  height: 90px;
  width: 100px;
  margin-left: 20px;
}
#Storename {
  float: right;
  margin-top: 30px;
}
.btn-edit {
  background-color: #f6993f;
  border-color: #f6993f;
}
.progress{
    height: 30px;
}
.form-group input[type="checkbox"] {
    display: none;
}

.form-group input[type="checkbox"] + .btn-group > label span {
    width: 20px;
}

.form-group input[type="checkbox"] + .btn-group > label span:first-child {
    display: none;
}
.form-group input[type="checkbox"] + .btn-group > label span:last-child {
    display: inline-block;
}

.form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
    display: inline-block;
}
.form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
    display: none;
}
.orderRows :hover{
 background-color: rgb(65, 65, 66);
}
img.preview {
    width: 200px;
    height: 200px;
    margin-left: 25%;
    background-color: white;
    border: 1px solid #DDD;
    padding: 5px;
}
.google-map {
  width: 80%;
  height: 400px;
  margin: 0 auto;
  background: gray;
}
#MessageButtonContainer {
  position: fixed;
  bottom: -4px;
  right: 10px;
}
#MessageThreadContainer {
  position: fixed;
  bottom: 10px;
  right: 20px;
  width: 600px;
  opacity: 1;
}
.nav-link{
    color: black !important;
}
.nav-link.active {
  background-color: rgb(65, 65, 66) !important;
  color: white !important;
}
.card-header{
    background-color: rgb(65, 65, 66);
    color : white;
}
</style>
