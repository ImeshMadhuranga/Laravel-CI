@include('theme.head')
<div class="preL display-none">
        <div class="preloader3 display-none"></div>
</div>
@if ($errors->any())
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
<div class="container">
        <div class="card">
          <div class="card-header">
              <h3 class="m-3 text-center text-dark ">
                  Enter Your Purchase code Detail
              </h3>
          </div>
          <div class="card-body" id="stepbox">
            <div class="text-center">
              <span class="text-danger">{{Session::get('vrfy_error');}}</span>
            </div>
               <form action="{{url('verifycode')}}" id="stepvform" method="POST" class="needs-validation" novalidate>                  
                  {{ csrf_field() }}
                   <h3>Envato Purchase details</h3>
                   <hr>
                  <div class="form-row">
                   
                    <br>
                    <div class="col-md-6 mb-3">
                      <label for="validationCustom01">Envato User Name:</label>
                      <input name="user_id" type="text" class="form-control" id="validationCustom01" placeholder="Username" value="" required>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      <div class="invalid-feedback">
                          Please fill name.
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="validationCustom01">Your site Domain:</label>
                      <input name="domain" type="text" class="form-control" id="validationCustom01" placeholder="Domain" value="" required>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      <div class="invalid-feedback">
                          Please fill Domain.
                      </div>
                    </div>
                    <div class="eyeCy col-md-6 mb-3">
                      <label for="validationCustom02">Envato Purchase Code:</label>
                      <input name="code" type="password" class="form-control" id="validationCustom02" placeholder="Please enter valid purchase code" value="" autocomplete="off" required>
                       <span toggle="#validationCustom02" class="eye fa fa-fw fa-eye field-icon toggle-password"></span>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      <div class="invalid-feedback">
                      </div>                          
                        @if($errors->any())
                          <h6 class="text-danger alert alert-error">{{$errors->first()}}</h6>
                        @endif

                        <br>
                        <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code" target="_blank">Where Is My Purchase Code ??</a>
                    </div>                    
                  </div>
                <button class="float-right step1btn btn btn-primary" type="submit">Please Verify</button>
              </form>
          </div>
        </div>
        <p class="text-center m-3 text-white">&copy;{{ date('Y') }} | eClass - Learning Management System | Installer v1.1 | <a class="text-white" href="http://mediacity.co.in">Media City</a></p>
      </div>
      @include('theme.scripts')
    
      
