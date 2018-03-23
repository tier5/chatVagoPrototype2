<script>
  var appID         = '{{env("FB_APP_ID")}}';
</script>

<p>All Steps</p>
        <!-- <div class="arrow-steps text-center clearfix">
            <div class="step current teststep disableBack" data-step="1"><span>Step 1</span> </div>
            <div class="step disableBack" data-step="2"><span>Step 2</span></div>
            <div class="step disableBack" data-step="3"><span>Step 3</span></div>
        </div> -->
        <div class="step stepOne visible text-center" style="display:block;">
            <p>To use this feature please connect your facebook account</p>
            <a href="javascript:void(0)" onclick="facebookLogin()" id="fbLoginBtn"><img src="/img/facebook-login-btn.png" class="img-responsive"></a>
        </div>
        <!-- <div class="step stepTwo">
            <p>Pages</p>
            <a href="javascript:void(0)" class="opendropdown">Please select a page <i class="fa fa-caret-down" aria-hidden="true"></i></a>
            <div class="dropdown-box"><ul></ul></div>
            <div class="table-responsive" id="formList"></div>
        </div> -->
        