@extends('layouts.app')
@section('content')
<script src="{{ url('/') }}/js/fb.js"></script>
<script>
  var appID         = '{{env("FB_APP_ID")}}';
  function genCSRF()
  {
    var csrf = '{{ csrf_token() }}';
    return csrf;
  }
</script>
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">My Dashboard</li>
      </ol>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-area-chart"></i>Facebook Login</div>
        <div class="card-body">
          <div class="modal fade" id="facebookleadModal" role="dialog">
              <div class="modal-dialog">
                   <!-- Modal content-->
                <div class="modal-content">
                    <div class="card-header">Facebook Page Analytics</div>
                     <div class="modal-body">
                       <p>Pages you manage, Click on the page to check analytics</p>
                       <div class="arrow-steps text-center clearfix">
                        <div class="step current teststep disableBack" data-step="1"><span>Step 1</span> </div>
                        <div class="step disableBack" data-step="2"><span>Step 2</span></div>
                      </div>
                      <div class="step stepOne text-center" style="display:block;">
                        <p>To use this feature please connect your facebook account</p>
                        <a href="javascript:void(0)" onclick="facebookLogin()" id="fbLoginBtn"><img src="/img/facebook-login-btn.png" class="img-responsive"></a>
                      </div>
                      <div class="step stepTwo text-center">
                        <p>Pages</p>
                        <div class="dropdown show">
                         <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Please select a page</a>
                         <div class="dropdown-menu dropdown-box" aria-labelledby="dropdownMenuLink">
                          <ul></ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                   <button type="button" class="btn btn-info btn-lg" data-dismiss="modal">Close</button>
                 </div>
                 <div class="loader-div" style="display:none;"></div>
                 <div class="transparent" style="display:none;"></div>
               </div>
            </div>
          </div>
          <div class="col-md-8 offset-md-5">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#facebookleadModal">
                {{ __('Facebook Analytics') }}
            </button>
          </div>
        </div>
        <div class="card-footer small text-muted"></div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-area-chart"></i></div>
        <div class="card-body">
          <form method="POST" action="{{ route('home') }}">
            @csrf
            <input type="hidden" name="fb_page_id" value="197724907493264">
          <div class="form-group">
            <label for="name">Page Access Token</label>
            <textarea id="page_access_token" name="page_access_token" class="form-control"  required autofocus>{{($page_access_token == '') ? '' : $page_access_token }}</textarea>
            @if ($errors->has('page_access_token'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('page_access_token') }}</strong>
                </span>
            @endif
          </div>
          <div class="col-md-8 offset-md-5">
          <button type="submit" class="btn btn-primary">
                {{ __('Submit') }}
            </button>
          </div>
        </form>
        </div>
        <div class="card-footer small text-muted"></div>
      </div>
    </div>
<script type="text/javascript">
  $( document).ready(function() {
        $(".arrow-steps .step").on('click', function(){
      var stepClicked = $(this).data('step');
      console.log(stepClicked);
      if (!$(this).hasClass('current') && !$(this).hasClass('disableBack')) {
        $('.step').removeClass('current');
        $(this).addClass('current');

        if(stepClicked == 1){
          $('.visible').removeClass('visible').hide();
          $('.stepOne').show().addClass('visible');
        } else {
          $('.visible').removeClass('visible').hide();
          $('.stepOne').hide();
          $('.stepTwo').show().addClass('visible');
        }
      }
    });
  });

</script>
@endsection