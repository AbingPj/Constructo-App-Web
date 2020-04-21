<template>
<div>
   <myHeader></myHeader>
    <div class="row">
        <div class="col-12 text-center">
            <p style="font-size: 19px; color : #479e48;">
            <span v-if="isRequestAccepted == true"> <i class="fas fa-check-circle"> </i> Request succesfully accepted </span>
            <span v-if="isRequestRejected == true"> <i class="fas fa-check-circle"> </i> Request succesfully rejected </span>
             </p>
        </div>
    </div>

    <!-- product category added succesfully message -->
    <div class="row">
        <div class="col-12 text-center">   <p style="font-size: 19px; color: #278c36;">
          <span v-if="productCategoryAdded == true"> <i class="fas fa-check-circle"> </i> Product category added succesfully </span>
           <span v-if="productCategoryEdited == true"> <i class="fas fa-check-circle"> </i> Product category edited succesfully </span>
         </p> </div>
    </div>


    <div class="row mr-3 ml-3">
        <div class="col-3">
            <div class="card" id="dashboardCard">
              <div class="card-body">
                   <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                     <a style="color:black;" class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true"> <i class="fa fa-tachometer-alt"></i>Dashboard </a>
                     <a class="nav-link" id="v-pills-request-tab" data-toggle="pill" href="#v-pills-request" role="tab" aria-controls="v-pills-request" aria-selected="false" @click="RequestNotifCount=0"><i class="far fa-clipboard"></i>Request <span class="badge badge-pill badge-danger" v-if="RequestNotifCount!=0">{{RequestNotifCount}}</span></a>
                     <a class="nav-link" id="v-pills-records-tab" data-toggle="pill" href="#v-pills-records" role="tab" aria-controls="v-pills-records" aria-selected="false" @click="fetchAllRecords();"><i class="far fa-folder"></i>Records</a>
                     <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-product" role="tab" aria-controls="v-pills-product" aria-selected="false"><i class="fas fa-list-ul"></i>Product Categories</a>
                </div>
              </div>
            </div>

        </div>
        <div class="col">

                         <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <div class="card">
                                    <div class="card-header">
                                       <span style="font-size:16px;"><i class="fa fa-tachometer-alt"></i> Dashboard </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                             <div class="card" id="dashboard sa maga cards makita ang all requests" @click="redirectToRequest()" style="cursor: pointer;">
                                                    <div class="card-content" style="background-color :rgb(65, 65, 66);color : white;">
                                                        <div class="text-center" style="margin-top:10px;">
                                                         <p class="h1" style="font-weight: 900;"><i class="fas fa-clock"> </i>{{requests.length}}</p> <br>
                                                         <b class="h5">Number of all pending requests</b>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="col-4">
                                                <div class="card" id="dashboard sa maga cards makita ang all registered users" @click="redirectToUsers()" style="cursor: pointer;">
                                                    <div class="card-content" style="background-color :rgb(65, 65, 66);color : white;">
                                                       <div class="text-center" style="margin-top:10px;">
                                                         <p class="h1" style="font-weight: 900;"><i class="fas fa-user"> </i>{{customersCount.length}}</p> <br>
                                                         <b class="h5">Number of All registered customer</b>
                                                        </div>
                                                     </div>
                                                 </div>
                                            </div>
                                             <div class="col-4">
                                                <div class="card" id="dashboard sa maga cards makita ang all registered users" style="cursor: pointer;" @click="redirectToUsers()"  >
                                                    <div class="card-content" style="background-color :rgb(65, 65, 66);color : white;">
                                                       <div class="text-center" style="margin-top:10px;">
                                                         <p class="h1" style="font-weight: 900;"><i class="fas fa-user-tie"> </i>{{storesCount.length}}</p> <br>
                                                         <b class="h5">Number of All registered stores</b>
                                                        </div>
                                                     </div>
                                                 </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-4">
                                                <div class="card" id="dashboard sa maga cards makita ang all requests" @click="redirectToProductCategory()" style="cursor: pointer;">
                                                    <div class="card-content" style="background-color :rgb(65, 65, 66);color : white;">
                                                        <div class="text-center" style="margin-top:10px;">
                                                         <p class="h1" style="font-weight: 900;"><i class="fas fa-list-ul"></i>{{productcategories.length}}</p> <br>
                                                         <b class="h5">Number of all product categories</b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="card" id="dashboard sa maga cards makita ang all requests" @click="redirectToRecords()" style="cursor: pointer;">
                                                    <div class="card-content" style="background-color :rgb(65, 65, 66);color : white;">
                                                        <div class="text-center" style="margin-top:10px;">
                                                         <p class="h1" style="font-weight: 900;"><i class="fas fa-check-circle"></i>{{validatedRecords.length}}</p> <br>
                                                         <b class="h5">Number of all validated records</b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="card" id="dashboard sa maga cards makita ang all requests">
                                                    <div class="card-content" style="background-color :rgb(65, 65, 66);color : white;">
                                                        <div class="text-center" style="margin-top:10px;">
                                                         <p class="h1" style="font-weight: 900;"> <i class="fas fa-hand-holding-usd"> </i> {{transactions.length}}</p> <br>
                                                         <b class="h5">Number of all transations</b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- records tab -->
                            <div class="tab-pane fade show ac" id="v-pills-records" role="tabpanel" aria-labelledby="v-pills-records-tab">
                                 <div class="card">
                                    <div class="card-header">
                                       <span style="font-size:16px;"><i class="far fa-folder"></i> Records </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mt-2 " style="font-size: 20px;" v-if="validatedRecords.length  != 0">
                                            <div class="col"><b> User Name</b> </div>
                                            <div class="col"><b> Request Type</b> </div>
                                            <div class="col"><b> Request Status</b> </div>
                                            <div class="col"><b> Date Accomodated</b> </div>
                                            <div class="col"><b> Accomodated By</b> </div>
                                        </div>
                                        <div class="row mt-2 border-bottom" v-for="validatedRecord in validatedRecords" v-if="validatedRecords.length != 0">
                                            <div class="col"> <p> {{validatedRecord.username}} </p> </div>
                                            <div class="col"> <p> {{validatedRecord.requestType}} </p> </div>
                                               <div class="col" v-if="validatedRecord.status == 'Accepted'"> <button class="btn btn-success" style="background-color: #fd7e14; border-radius: 0px; cursor: no-drop;" disabled> {{validatedRecord.status}} </button> </div>
                                               <div class="col" v-else> <button class="btn btn-danger" style="border-radius: 0px;" v-on:click="viewConcern(validatedRecord.concern)"> Rejected</button> </div>
                                            <div class="col"> <p> {{validatedRecord.accomodated_date}} </p> </div>
                                            <div class="col"> <p> {{validatedRecord.adminname}} </p> </div>
                                        </div>
                                        <div class="row mt-2 justify-content-center" v-if="validatedRecords.total != 0">
                                            <div class="col-5">
                                              <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchRecordsPaginate(recordspagination.prev_page_url)" :disabled="!recordspagination.prev_page_url"> <i class="fas fa-chevron-circle-left"> </i> Prev</button>
                                             </div>
                                            <div class="col-2">
                                              <span> page {{recordspagination.current_page}} of {{recordspagination.last_page}}</span>
                                            </div>
                                            <div class="col-5">
                                                  <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchRecordsPaginate(recordspagination.next_page_url)" :disabled="!recordspagination.next_page_url"> <i class="fas fa-chevron-circle-right"> </i> Next</button>
                                            </div>
                                        </div>
                                        <div class="row" v-if="validatedRecords.total  == 0">
                                            <div class="col-12 text-center"> Records Empty. </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Request Tab -->
                            <div class="tab-pane fade" id="v-pills-request" role="tabpanel" aria-labelledby="v-pills-request-tab">
                               <div class="card">
                                    <div class="card-header">
                                       <span style="font-size:16px;"><i class="far fa-clipboard"></i> Requests </span>
                                    </div>
                                    <div class="card-body" >
                                        <div class="row">
                                              <div class="col">
                                              <label for="sortUser">Sort By Request Type:</label>
                                                <select id="sortUser" class="form-control" v-model="requestsFilter.type">
                                                     <option v-bind:value="0"> All</option>
                                                     <option v-bind:value="1"> Registration</option>
                                                     <option v-bind:value="2"> Subscription</option>
                                                     <option v-bind:value="3"> Concern</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                               <button class="btn form-control" style="margin-top:30px; border-radius:0px; background-color: #fd7e14; color:white;" v-on:click="searchFilteredRequest()"><i class="fas fa-search"> </i>Search </button>
                                            </div>
                                             <div class="col"> </div>

                                        </div>
                                    </div>

                                   <div class="card ml-3 mr-2 mb-2 "  v-if="requests.length != 0"  v-for="request in requests">
                                      <div class="card-body" v-if="requests.length != 0" >
                                          <div class="row">
                                              <div class="col-4">
                                                   <span style="font-size: 21px; text-transform:capitalize;">{{request.username}}  </span>
                                              </div>
                                              <div class="col-4">
                                                    <span style="font-size: 16px; text-transform:capitalize;" id="requestStatus" class="mt-2"> {{request.status}} </span>
                                                    <span style="font-size: 16px; text-transform:capitalize;"> {{request.requestType}}</span>
                                              </div>
                                              <div class="col-4 text-center">
                                                  <button aria-describedby="requestStatus" class="btn " style="border-radius:0px; background-color: #fd7e14; color :white;" v-on:click="launchRequsetModal(request.id,request.requestTypeId,0)" v-if="request.status=='Pending'"> <i class="fas fa-hands"> </i>Manage Request</button>
                                                  <button aria-describedby="requestStatus" class="btn " style="border-radius:0px; background-color: #fd7e14; color :white;" v-on:click="launchRejectedRequestModal(request.id,request.requestTypeId)" v-else-if="request.status=='with Concerns'"> Manage Request</button>
                                                   <button aria-describedby="requestStatus" class="btn btn-primary" style="border-radius:0px; background-color: #fd7e14;" v-on:click="launchRequsetModal(request.id,request.requestTypeId,1)" v-else> View Request</button>

                                              </div>
                                          </div>

                                      </div>
                                   </div>
                                   <div class="card-body">
                                        <div class="row" v-if="requests.length == 0">
                                               <div class="col-12 text-center">Empty requests  </div>
                                           </div>
                                   </div>
                                </div>
                                </div>
                                 <!-- Product Categories-->
                                 <div class="tab-pane fade" id="v-pills-product" role="tabpanel" aria-labelledby="v-pills-product-tab">
                               <div class="card">
                                    <div class="card-header">
                                       <span style="font-size: 16px;"><i class="fas fa-list-ul"></i> Product Categories </span>
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                           <div class="col-7">
                                               <input class="form-control" placeholder="Search..." style="border-radius: 0px;" v-model="searchProductCategory">
                                           </div>
                                           <div class="col-5">
                                               <button class="btn " style="border-radius:0px;background-color:#fd7e14; color: white;"  data-toggle="modal" data-target="#addProductCategory"><i class="fas fa-plus-circle"> </i>Add Category </button>
                                           </div>
                                        </div>

                                    <div >
                                            <div class="card mt-3" v-for="productcategory in productcategories" v-if="showLoading == false && searchProductCategory == ''">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col">
                                                               <p style="text-transform:capitalize;"> {{productcategory.category}} </p>
                                                        </div>
                                                        <div class="col-4">
                                                                <button class="btn" style="color: white; font-size: 16px; border-radius:0px; background-color:#fd7e14;" data-toggle="modal" data-target="#editProductCategory" v-on:click="viewProduct(productcategory.id,productcategory.category)"> <i class="fas fa-edit"> </i>Edit</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="card mt-3" v-for="productcategory in filteredCategory" v-if="showLoading == false && searchProductCategory != ''">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col">
                                                               <p style="text-transform:capitalize;"> {{productcategory.category}} </p>
                                                        </div>
                                                        <div class="col-4">
                                                                <button class="btn" style="color: white; font-size: 16px; border-radius:0px; background-color:#fd7e14;" data-toggle="modal" data-target="#editProductCategory" v-on:click="viewProduct(productcategory.id,productcategory.category)"> <i class="fas fa-edit"> </i>Edit</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                    </div>
                                    <div class="row mt-2 justify-content-center" v-if="productcategories.total != 0 && searchProductCategory == ''">
                                            <div class="col-5">
                                              <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchProductCategoryPaginate(categorypagination.prev_page_url)" :disabled="!categorypagination.prev_page_url"> <i class="fas fa-chevron-circle-left"> </i> Prev</button>
                                             </div>
                                            <div class="col-2">
                                              <span> page {{categorypagination.current_page}} of {{categorypagination.last_page}}</span>
                                            </div>
                                            <div class="col-5">
                                                  <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchProductCategoryPaginate(categorypagination.next_page_url)" :disabled="!categorypagination.next_page_url"> <i class="fas fa-chevron-circle-right"> </i> Next</button>
                                            </div>
                                        </div>
                                              <div class="progress mt-3" v-if="showLoading == true">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                              </div>
                                     </div>
                                </div>
                                </div>

                          </div>


        </div>
    </div>

    <!-- Concern Modal -->
<div class="modal fade" id="concernModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Rejected Request Concern Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row">
             <div class="col-12 ml-3"> Concern :</div>
             <div class="col-12 mt-2 ml-3">
                 <p> {{concernMsg}} </p>
             </div>
             <div class="col-12">
        <button type="button" class="btn btn-primary form-control" style="border-radius: 0px;" data-dismiss="modal">Got It</button>
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
               <div class="row">
                   <div class="col-6"> <button type="button" class="btn form-control" style="border-radius: 0px; background-color: #fd7e14 !important;" v-on:click="Logout()">Yes</button>
                   </div>
                   <div class="col-6">
                     <button type="button"  style="border-radius: 0px; background-color: #fd7e14; !important;" class="btn btn-primary" data-dismiss="modal">No</button>
                   </div>
               </div>
      </div>
    </div>
  </div>
</div>
<!-- Add Product Category Modal -->
<div class="modal fade" id="addProductCategory" tabindex="-1" role="dialog" aria-labelledby="addProductCategory" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductCategory">Product Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form>
              <div class="form-group">
                 <label for="exampleInputEmail1">Category Name</label>
                   <input type="text" v-model="categoryname" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                   <small id="emailHelp" class="form-text text-muted">This category shall be used by the store to categorize their product</small>
            </div>
        </form>
        <div class="row">
                   <div class="col-6">
         <button type="button" class="btn form-control" style="background-color: #fd7e14; color: white; border-radius: 0px;" v-on:click="addProductCategory()"><i class="fas fa-plus-circle"> </i>Add</button>
                   </div>
                   <div class="col-6">
        <button type="button" class="btn form-control" style="background-color: #fd7e14; color: white; border-radius: 0px;" data-dismiss="modal"> <i class="fas fa-times-circle"> </i> Cancel</button>
                   </div>
               </div>
      </div>
    </div>
  </div>
</div>
<!-- Edit Product Category Modal -->
<div class="modal fade" id="editProductCategory" tabindex="-1" role="dialog" aria-labelledby="addProductCategory" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductCategory">Product Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form>
              <div class="form-group">
                 <label for="exampleInputEmail1">Category Name</label>
                  <input type="text" v-model="category.name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                  <small id="emailHelp" class="form-text text-muted">Changes of the Category name shall be saved. change wisely</small>
            </div>
        </form>
        <div class="row">
               <div class="col-6">
                 <button type="button" class="btn form-control" style="background-color: #fd7e14; color: white; border-radius: 0px;" v-on:click="updateProductCategory()"> <i class="fas fa-save"> </i> Save Changes</button>
               </div>
               <div class="col-6">
                  <button type="button" class="btn form-control" style="background-color: #fd7e14; color: white; border-radius: 0px;" data-dismiss="modal"> <i class="fas fa-times-circle"> </i> Cancel</button>
               </div>
      </div>
      </div>

    </div>
  </div>
</div>
<!-- Accept Request Modal -->
<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="addProductCategory" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductCategory">

            <span v-if="isRequestView == true"> Are you sure to accept this request ? </span>
             <span v-else> Accepted Request </span>
            </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" v-for="singlereq in singleRequest">
          <div class="form-group">
              <label>From : </label>
              <input type="text" disabled class="form-control" v-model="singlereq.fullname">
          </div>
          <div class="form-group">
              <label> Location : </label>
              <input type="text" disabled class="form-control" v-model="singlereq.address">
          </div>
           <div class="form-group">
              <label> Phone Number : </label>
              <input type="text" disabled class="form-control" v-model="singlereq.phone">
          </div>
           <div class="form-group">
              <label> Email Address : </label>
              <input type="text" disabled class="form-control" v-model="singlereq.email">
          </div>
          <div class="form-group" v-if="singlereq.categoryrequest">
              <label> Category To be Requested : </label>
              <input type="text" disabled class="form-control" v-model="singlereq.categoryrequest">
          </div>
         <div v-if="isRequestView == true "> <!-- only for pending requests -->
          <button class="btn " style="background-color:#fd7e14; border-radius:0px; color: white;" v-on:click="requesthasConcern = !requesthasConcern">

              <span v-if="requesthasConcern==true"><i class="fas fa-times-circle"> </i> Close </span>
              <span v-else><i class="fas fa-thumbs-down"> </i> Reject Request with concern </span>
              </button>
          <div class="form-group mt-3" v-if="requesthasConcern==true">
              <label> Message : </label>
              <textarea class="form-control" placeholder="Type your concern here..." style="height:120px;" v-model="concernMessage"></textarea>
          </div>

           <div class="modal-footer justify-content-center">
         <button type="button" class="btn " style="background-color:#fd7e14; border-radius:0px; color: white;" v-if="requesthasConcern==false" v-on:click="acceptRequest(singlereq.categoryrequest)"><i class="fas fa-thumbs-up"> </i>Accept Request</button>
         <button type="button" class="btn " style="background-color:#fd7e14; border-radius:0px; color: white;" v-else v-on:click="rejectRequest(singlereq.id)"> <i class="fas fa-thumbs-down"> </i>Reject Request</button>
        <button type="button" class="btn " style="background-color:#fd7e14; border-radius:0px; color: white;" data-dismiss="modal"><i class="fas fa-times-circle"> </i>Cancel</button>
      </div>
      </div>

      </div>
    </div>
  </div>
</div>
<!-- Viewing rejected requests -->
<div class="modal fade" tabindex="-1" role="dialog" id="viewingReject" >
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Request Rejected with Concern</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" v-for="rejected in rejectedRequest">
        <div class="form-group">
            <label> From :</label>
            <input type="text" disabled class="form-control" v-model="rejected.fullname">
        </div>
        <div class="form-group">
            <label> Location : </label>
            <input type="text" disabled class="form-control" v-model="rejected.address">
        </div>
        <div class="form-group">
            <label> Phone number : </label>
            <input type="text" disabled class="form-control" v-model="rejected.phone">
        </div>
        <div class="form-group">
            <label> Email Address :  </label>
            <input type="text" disabled class="form-control" v-model="rejected.email">
        </div>
        <div class="form-group mt-3">
              <label> Message : </label>
              <textarea class="form-control"  style="height:120px;" disabled v-model="rejected.concern"></textarea>
          </div>
      </div>
    </div>
  </div>

</div>

</div>
</template>
<script>
 import myHeader from './adminHeader';
export default {
    components :{
        'myHeader' : myHeader
    },
 data : function(){
   return{
    adminId : localStorage.getItem('id'),
    adminAccount : [],
    categoryname : '',
    productcategories : [],
    recordspagination : [],
    categorypagination : [],
    productcategoryisadd : true,
    category : {
        id : 0,
        name : ''
    },
    requests : [],
    requestsFilter : {
        type : 0
    },
    requestModal : {
        id : 0,
        type : 0
    },
    singleRequest :[],
    requesthasConcern : false,
    isRequestView : false,
    concernMessage : '',
    rejectedRequest : [],
    notifMessage : '',
    RequestNotifCount : 0,
    showLoading : false,
    validatedRecords: [],
    concernMsg : '',
    categoryToBeAccepted: '',
    customersCount: 0,
    storesCount: 0,
    transactions : [],
    searchProductCategory : '',
    productCategoryAdded : false,
    productCategoryEdited : false,
    isRequestAccepted : false,
    isRequestRejected : false
   }
 },

 mounted : function(){
     if(localStorage.getItem('id'===null)){
            this.$router.push('/');
     }else{
         this.checkUserVisibility();
           this.listenToRequest();
           this.listenToSubscriptionRequest();

         setTimeout( ()=>{
            this.getAdminData();
            this.fetchProductCategory();
            this.fetchAllRequest();
         },2000);

            this.getCustomerCount();
            this.getstoresCount();
            this.getAllTransaction();
            this.fetchAllRecords();

     }

   },
   computed : {
     filteredCategory : function(){
        return this.productcategories.filter( category => {
            return category.category.toLowerCase().includes(this.searchProductCategory);
        })
     }
   },
 methods : {

     redirectToUsers : function(){
       this.$router.push({ name : 'AdminUsers'});
     },
     redirectToRequest : function(){
         $('#v-pills-request-tab').tab('show');
     },
     redirectToProductCategory : function(){
         $('#v-pills-messages-tab').tab('show');
     },
     redirectToRecords : function(){
         $('#v-pills-records-tab').tab('show');
     },
     getAllTransaction : function(){
        axios.get('api/getAllTransaction').then(response=>{
        this.transactions = response.data;
        });
     },
     viewConcern : function(concern){
        this.concernMsg = concern;
        $('#concernModal').modal('show');
     },
     fetchAllRecords : function(){
        axios.get('api/fetchAllRecords').then(response=>{
           this.validatedRecords = response.data.data;
           this.makeRecordsPaginate(response.data);
        });
     },
     makeRecordsPaginate : function(data){
       let pagination = {
           current_page : data.current_page,
           last_page : data.last_page,
           next_page_url : data.next_page_url,
           prev_page_url : data.prev_page_url
       }
       this.recordspagination = pagination;
     },
     fetchRecordsPaginate : function(url){
       axios.get(url).then(resp => {
           this.validatedRecords = resp.data.data;
           this.makeRecordsPaginate(resp.data);
       });
     },
     listenToSubscriptionRequest : function(){
       Echo.channel('subscriptionChannel').listen('StoreSubscriptionRequest', message=>{
           this.RequestNotifCount = this.RequestNotifCount + 1;
           fetchAllRequest();
       });
     },
     sendSubscriptionRequest : function(){
         this.showLoading = true;
      let obj= {requestId : this.requestModal.id, requestType : this.requestModal.type, accomodatedBy : this.adminId };
      this.isRequestAccepted = true;
       axios.post('api/acceptRequest',obj).then( (response)=>{
          this.fetchAllRequest();
           this.showLoading = false;
          $('#requestModal').modal('hide');
           setTimeout(()=>{
               this.isRequestAccepted = false;
           },3000);
      }, (error)=>{
       this.fetchAllRequest();
          $('#requestModal').modal('hide');
          this.showLoading = false;
           setTimeout(()=>{
               this.isRequestAccepted = false;
           },3000);
      });
      this.fetchAllRequest();
      $('#requestModal').modal('hide');
     },
     listenToRequest : function(){
      Echo.channel('storeRegistration').listen('StoreRegistrationEvent', message=>{
       this.RequestNotifCount = this.RequestNotifCount + 1;
          fetchAllRequest();
      });
     },
     rejectRequest : function(id){
       let obj = {requestId : id, requestConcern : this.concernMessage, accomodatedBy : this.adminId};
        this.isRequestRejected = true;
        setTimeout(()=>{
            axios.post('api/rejectRequest',obj).then(response=>{
            this.fetchAllRequest();
             $('#requestModal').modal('hide');
              setTimeout(()=>{
                  this.isRequestRejected = false;
              }, 3000);
            },error=>{
              this.fetchAllRequest();
              $('#requestModal').modal('hide');
              setTimeout(()=>{
                  this.isRequestRejected = false;
              }, 3000);
           });
          },2000);
     },
     getAdminData  : function(){
       let app = this;
       let obj = {id : this.adminId};
       axios.post('api/getAdminAccount',obj).then(resp=>{
        app.adminAccount = resp.data;
        console.log(resp.data);
       });
     },
     launchRejectedRequestModal : function(id,type){

        let app = this;
        let obj = {requestId : id};
         axios.post('api/viewRejectedRequest',obj).then( response=> {
            app.rejectedRequest = response.data;
              $('#viewingReject').modal('show');
         });
     },
     viewRejectedRequest : function(){
     },
     launchRequsetModal : function(id,type,isView){
       // 1 is for viewing request, 0 is for accepting or rejecting, 2 is for viewing rejected request
       isView ==0 ? this.isRequestView = true : this.isRequestView = false;
        this.requesthasConcern = false;
          let app = this;
          this.requestModal.id = id;
         this.requestModal.type = type;
       let obj = {id : this.requestModal.id}

       axios.post('api/viewSingleRequest',obj).then(response =>{
           app.singleRequest = response.data;
           $('#requestModal').modal('show');
         });
     },
     acceptRequest : function(category){
      let obj= {requestId : this.requestModal.id, requestType : this.requestModal.type, accomodatedBy : this.adminId, category : category} ;
       axios.post('api/acceptRequest',obj).then( (response)=>{}, (error)=>{
        this.fetchAllRequest();
          $('#requestModal').modal('hide')
       });
       this.fetchAllRequest();
       $('#requestModal').modal('hide')
     },
     Logout : function(){
       $('#logoutModal').modal('hide');
       localStorage.removeItem('id');
       this.$router.push('/');
     },
     checkUserVisibility :function(){
       if(localStorage.getItem('id')===null){
        this.$router.push('/');
       }
     },
     addProductCategory : function(){
      let obj = {category : this.categoryname};
      this.productCategoryAdded = true;
      axios.post('api/adminAddingProductCategory',obj).then(resp => {

        $('#addProductCategory').modal('hide');
          this.fetchProductCategory();
          setTimeout(()=>{ this.productCategoryAdded = false; },3000);
     });

   },
   fetchProductCategory : function(){
      let app = this;
      this.showLoading = true;
        axios.get('api/fetchProductCategory').then(response=>{
          app.productcategories = response.data.data;
          this.makeProductCategoryPaginate(response.data);
          this.showLoading = false;
    });
   },
   makeProductCategoryPaginate : function(data){
    let pagination = {
           current_page : data.current_page,
           last_page : data.last_page,
           next_page_url : data.next_page_url,
           prev_page_url : data.prev_page_url
    };
    this.categorypagination = pagination;
   },
   fetchProductCategoryPaginate : function(url){
     axios.get(url).then(resp =>{
         this.productcategories = resp.data.data;
         this.makeProductCategoryPaginate(resp.data);
     })
   },
   viewProduct  : function(id,name){
     this.category.id = id;
     this.category.name = name;
   },
   updateProductCategory : function(){
       this.productCategoryEdited = true;
       axios.post('api/updateProductCategory',this.category).then(resp =>{
          $('#editProductCategory').modal('hide');
          this.fetchProductCategory();
          setTimeout(()=>{
              this.productCategoryEdited = false;
          },2000);
       });

   },
   fetchAllRequest : function(){
      let app =this;
       axios.get('api/fetchRequestAll').then(response=>{
         app.requests = response.data;
       });
   },
   searchFilteredRequest : function(){
      if(this.requestsFilter.type == 0){
        this.fetchAllRequest();
      }else{
          let obj = {requestType : this.requestsFilter.type};
            axios.post('api/fetchRequestwithFilter',obj).then(response=>{
              this.requests = response.data;
            });
      }
   },
   getCustomerCount : function(){
       axios.get('api/customerCount').then(response=>{
         this.customersCount = response.data;
       });
   },
   getstoresCount : function(){
        axios.get('api/customerCount').then(response=>{
         this.storesCount = response.data;
       });
   }

 },



}
</script>
<style>
#dashboardCard, #v-pills-tabContent{
    margin-top : 30px;
}
 #navbar{
    box-shadow: 1px 1px 1px 1px #cccccc;
 }
 #adminProfile{
     height: 90px;
     width: 100px;
     margin-left: 20px;
 }
  #regcard{

     margin-top:20px;
     margin-left : 30%;
     right : 0;
     box-shadow: 1px 1px 1px 1px #cccccc;
 }
 #AdminName{
     float: right;
     margin-top: 30px;
 }
 #adminBanner{
     float: right;
     margin-top: 50px;
 }
 .btn-edit{
     background-color: #f6993f;
     border-color: #f6993f;
 }
 .btn-add{
     border-radius: 50%;
     width: 40px;;
     margin-left: 10px;
 }
 .progress{
    height: 30px !important;
}
.card-header{
  background-color: rgb(65, 65, 66);
  color: white;
}
.nav-link{
    color: black !important;
}
.nav-link.active {
  background-color: rgb(65, 65, 66) !important;
  color: white !important;
}
i{
    margin-right: 10px;
}
</style>


