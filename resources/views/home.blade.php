@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<script src="{{ url('/') }}/js/fb.js"></script>
<script>
  var appID         = '{{env("FB_APP_ID")}}';
  function genCSRF()
  {
    var csrf = '{{ csrf_token() }}';
    return csrf;
  }
</script>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8" style="margin-bottom: 50px;">
      <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">ChatVago</div>
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
   <div class="modal fade" id="boardcastModal" role="dialog">
    <div class="modal-dialog">
     <!-- Modal content-->
     <div class="modal-content">
      <div class="card-header">Send Message</div>
      <div class="modal-body">
       <input type="hidden" name="csrf_token" content="{{ Session::token() }}">
       <div class="form-group row">
        <label for="email" class="col-md-2 col-form-label text-md-right">{{ __('Message') }}</label>
        <div class="col-md-10">
          <textarea class="form-control" rows="10" id="send_message_text" name="send_message_text"></textarea>
        </div>
      </div>
      <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-5">
          <button type="submit" class="btn btn-info btn-lg" id="sendMessage">
            {{ __('Send') }}
          </button>
        </div>
      </div>
    </div>
    <div class="loader-div" style="display:none;"></div>
    <div class="transparent" style="display:none;"></div>
  </div>
</div>
</div>
<div class="form-group row mb-0">
  <div class="col-md-8 offset-md-4">
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#facebookleadModal">Facebook Analytics</button>
  </div>
</div>
</div>
</div>
<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('home') }}">
      @csrf
      <input type="hidden" name="fb_page_id" value="197724907493264">

      <div class="form-group row">
        <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Date Rang') }}</label>
        <div class="col-md-10">

          <select id="dateRange" name="dateRange" class="form-control">
            <option value="1day" @if($oneWeekTime == '1day') selected="selected" @endif>1 Day</option>
            <option value="7days" @if($oneWeekTime == '7days') selected="selected" @endif>7 Days</option>
            <option value="1month" @if($oneWeekTime == '1month') selected="selected" @endif>1 Month</option>
          </select> 
        </div>
      </div>

      <div class="form-group row">
        <label for="email" class="col-md-2 col-form-label text-md-right">{{ __('Page Access Token') }}</label>

        <div class="col-md-10">
          <textarea class="form-control" rows="10" id="page_access_token" name="page_access_token">{{($page_access_token == '') ? '' : $page_access_token }}</textarea>
        </div>
      </div>

      <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-5">
          <button type="submit" class="btn btn-info btn-lg">
            {{ __('Submit') }}
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
</div>

@if($fb_page_id != '')
<div class="card" style="margin-bottom: 30px;">
  <div class="card-header">Analytics</div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Analytics</th>
          <th scope="col">Value</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">page_messages_reported_conversations_by_report_type_unique</th>
          <td><table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">Date</th>
                <th scope="col">Spam</th>
                <th scope="col">Inappropriate</th>
                <th scope="col">Other</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tbody>
              @if(count($page_messages_reported_conversations_by_report_type_unique) > 0)
              @php($report_type_unique_total = 0)
              @foreach ($page_messages_reported_conversations_by_report_type_unique as $key => $report_type_unique)
              <tr>
                <th scope="row">{{date('Y-m-d',strtotime($report_type_unique['end_time']))}}</th>
                <td>{{(array_key_exists('spam', $report_type_unique['value']) == '') ? 0 : $report_type_unique['value']['spam'] }}</td>
                <td>{{(array_key_exists('inappropriate', $report_type_unique['value']) == '') ? 0 : $report_type_unique['value']['inappropriate'] }}</td>
                <td>{{(array_key_exists('other', $report_type_unique['value']) == '') ? 0 : $report_type_unique['value']['other'] }}</td>
                <td>{{$report_type_unique['value']['spam'] + $report_type_unique['value']['inappropriate'] + $report_type_unique['value']['other']}}</td>
                @php($report_type_unique_total += $report_type_unique['value']['spam'] + $report_type_unique['value']['inappropriate'] + $report_type_unique['value']['other'])
              </td>
            </tr>
            @endforeach
            <tr><th scope="row" colspan="2">Total</th><th scope="row" colspan="3">{{$report_type_unique_total}}</th></tr>
            @else
            <tr>
              <td colspan="5" style="text-align:center;">No Record Found</td>
            </tr>
            @endif
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <th scope="row">page_messages_new_conversations_unique</th>
      <td><table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">Date</th>
            <th scope="col">Value</th>
          </tr>
        </thead>
        <tbody>
          @if(count($page_messages_new_conversations_unique) > 0)

          @php($conversations_unique_total = 0)

          @foreach ($page_messages_new_conversations_unique as $key => $new_conversations_unique)
          <tr>
            <th scope="row">{{date('Y-m-d',strtotime($new_conversations_unique['end_time']))}}</th>
            <td>{{(array_key_exists('value', $new_conversations_unique) == '') ? 0 : $new_conversations_unique['value'] }}</td>
            @php($conversations_unique_total += (array_key_exists('value', $new_conversations_unique) == '') ? 0 : $new_conversations_unique['value'])
          </tr>
          @endforeach
          <tr><th scope="row">Total</th><th scope="row" colspan="3">{{$conversations_unique_total}}</th></tr>
          @else
          <tr>
            <td colspan="5" style="text-align:center;">No Record Found</td>
          </tr>
          @endif
        </tbody>
      </table>
    </td>
  </tr>
</tr>
<tr>
  <th scope="row">page_messages_open_conversations_unique</th>

  <td><table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Value</th>
      </tr>
    </thead>
    <tbody>
      @if(count($page_messages_open_conversations_unique) > 0)
      @php($open_conversations_unique_total = 0)
      @foreach ($page_messages_open_conversations_unique as $key => $open_conversations_unique)
      <tr>
        <th scope="row">{{date('Y-m-d',strtotime($open_conversations_unique['end_time']))}}</th>
        <td>{{(array_key_exists('value', $open_conversations_unique) == '') ? 0 : $open_conversations_unique['value'] }}</td>
        @php($open_conversations_unique_total += (array_key_exists('value', $open_conversations_unique) == '') ? 0 : $open_conversations_unique['value'])
      </tr>
      @endforeach
      <tr><th scope="row">Total</th><th scope="row">{{$open_conversations_unique_total}}</th></tr>
      @else
      <tr>
        <td colspan="5" style="text-align:center;">No Record Found</td>
      </tr>
      @endif
    </tbody>
  </table></td>
</tr>
<tr>
  <th scope="row">page_messages_blocked_conversations_unique</th>

  <td><table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Value</th>
      </tr>
    </thead>
    <tbody>
      @if(count($page_messages_blocked_conversations_unique) > 0)
      @php($blocked_conversations_unique_total = 0)
      @foreach ($page_messages_blocked_conversations_unique as $key => $blocked_conversations_unique)
      <tr>
        <th scope="row">{{date('Y-m-d',strtotime($blocked_conversations_unique['end_time']))}}</th>
        <td>{{(array_key_exists('value', $blocked_conversations_unique) == '') ? 0 : $blocked_conversations_unique['value'] }}</td>
        @php($blocked_conversations_unique_total += (array_key_exists('value', $blocked_conversations_unique) == '') ? 0 : $blocked_conversations_unique['value'])
      </tr>
      @endforeach
      <tr><th scope="row" >Total</th><th scope="row" >{{$blocked_conversations_unique_total}}</th></tr>
      @else
      <tr>
        <td colspan="5" style="text-align:center;">No Record Found</td>
      </tr>
      @endif
    </tbody>
  </table></td>
</tr>
<tr>
  <th scope="row">page_messages_active_threads_unique</th>

  <td><table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Value</th>
      </tr>
    </thead>
    <tbody>
      @if(count($page_messages_active_threads_unique) > 0)
      @php($active_threads_unique_total = 0)
      @foreach ($page_messages_active_threads_unique as $key => $active_threads_unique)
      <tr>
        <th scope="row">{{date('Y-m-d',strtotime($active_threads_unique['end_time']))}}</th>
        <td>{{(array_key_exists('value', $active_threads_unique) == '') ? 0 : $active_threads_unique['value'] }}</td>
        @php($active_threads_unique_total += (array_key_exists('value', $active_threads_unique) == '') ? 0 : $active_threads_unique['value'])
      </tr>
      @endforeach
      <tr><th scope="row" >Total</th><th scope="row">{{$active_threads_unique_total}}</th></tr>
      @else
      <tr>
        <td colspan="5" style="text-align:center;">No Record Found</td>
      </tr>
      @endif
    </tbody>
  </table></td>
</tr>
</tbody>
</table>
</div>
</div>
@endif
</div>
<div class="row justify-content-center">
  <div class="card">
    <div class="card-header">Broadcast</div>
    <div class="card-body">
      <h4 class="row justify-content-center">API LINK : https://chatvago.tier5-development.us/{{ Auth::user()->id }}/insertUser?psid={{($pageScopeUserId == '') ? 'PSID' : $pageScopeUserId}}</h4>

      <div style="float: left; margin-top: 20px; margin-bottom: 20px;">
        <button type="button" class="btn btn-primary px-5" data-toggle="modal" data-target="#boardcastModal">
        Send Message</button></div>
        <div style="float: right; margin-top: 20px; margin-bottom: 20px;">
          <button type="button" class="btn btn-danger px-5" id="btn_delete" name="btn_delete">Delete
          </button></div>
          <div class="table-responsive" id="datatableDiv">
            <table class="table table-bordered" id="dataTable" cellspacing="0">
              <thead>
                <tr>
                  <th><input type="checkbox" name="ckbCheckAll" id="ckbCheckAll"></th>
                  <th>Profile Pic</th>
                  <th>First Name </th>
                  <th>Last Name</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($getBroadcastDetail as $key => $getBroadcastUser)
                <tr>
                  <th><input type="checkbox" name="checkUser" id="checkUser" value="{{$getBroadcastUser->fb_id}}"></th>
                  <th><img src="{{$getBroadcastUser->profile_picture}}" width="80" height="80"></th>
                  <th>{{$getBroadcastUser->first_name}}</th>
                  <th>{{$getBroadcastUser->last_name}}</th>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-8" style="margin-top: 50px;">
        <div class="card" style="margin-bottom: 30px;">
          <div class="card-header">Messenger Code</div>
          <div class="card-body">
            <div class="alert alert-danger" id="showErrorMessage" style="display: none;">
            </div>
            <div class="form-group row">
              <label for="ref" class="col-md-3 col-form-label text-md-right">Data Ref</label>
              <div class="col-md-8">
                <input type="text" name="messenger_ref" id="messenger_ref" class="form-control">
              </div>
            </div>
            <div class="form-group row">
              <label for="ref" class="col-md-3 col-form-label text-md-right">Image Size Range (Supported range: 100-2000 px)</label>
              <div class="col-md-8">
                <input type="text" name="image_size" id="image_size" class="form-control">
              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-8 offset-md-5">
                <button type="button" class="btn btn-info btn-lg" id="generateMessagerImage">Generate</button> 
              </div>
            </div>

            <div class="form-group row">
              &nbsp;
            </div>
          <div id="showImage" style="display: none;">
            <div class="form-group">
              <div class="col-md-8 offset-md-3">
                <!-- <img src="{{URL::asset('/img/blank.png')}}" id="messengerImage" width="70%" height="70%"/> -->

                <img src="" id="messengerImage" width="70%" height="70%"/>
                

              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-8 offset-md-5">
                <a href="" class="btn btn-info btn-lg" id="downloadImg" name="code.png" download="code" id="downloadImg">Download</a>

               <!--  <button class="btn btn-info btn-lg" id="downloadImageFile">Download</button> -->
              </div>

            </div>
          </div>
            <div class="loader" id="modalLoader" style="display: none;"></div>
          </div>
        </div>


      </div>
    </div>
  </div>
</div>
</div>
</div>
<script type="text/javascript">
  $( document).ready(function() {

    $('#showErrorMessage').hide();
    $('#downloadImage').hide();
    $('#modalLoader').hide();

    $('#ckbCheckAll').click(function(){
      if (this.checked == false) {
        $('#checkUser:checked').attr('checked', false);
      }
      else {
        $('#checkUser:not(:checked)').attr('checked', true);
      }
    });

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

    $("#generateMessagerImage").click(function(){
      var messengerURL = "{{route('messenger_image') }}";
      var messenger_ref = $('#messenger_ref').val();
      var image_size = $('#image_size').val();

      if(messenger_ref == '') {

        // $('#showErrorMessage').show();
        // 
        $('#showErrorMessage').css('display','block');
        $('#showErrorMessage').text('Please enter data ref');

      } else if(image_size != '' && image_size < 100 || image_size > 2000) { 

       //$('#showErrorMessage').show();
       //
       $('#showErrorMessage').css('display','block');
       $('#showErrorMessage').text('supported range: 100-2000 px');
     }
     else {

            $('#modalLoader').css('display','block');

            $.ajax({

             type:'POST',

             url:messengerURL,

             data:{_token: '{{csrf_token()}}', messenger_ref: messenger_ref, image_size: image_size},

             success:function(data){

              var jsonData = JSON.parse(data);

              var getTotalImagePath = jsonData.uri;

              $('#showImage').css('display','block');

              $('#messengerImage').attr("src", getTotalImagePath);

              $('#downloadImg').attr("href", getTotalImagePath);

              $('#modalLoader').css('display','none');

            }

          });

          }
        });

        // Messenger Image Code Generate
        
        $("#sendMessage").click(function(){
          var chooseUser = [];
          var postURL = "{{route('boardcast') }}";
          $.each($("input[name='checkUser']:checked"), function(){            
            chooseUser.push($(this).val());
          });

          var getMesg = $('#send_message_text').val();


          $.ajax({

           type:'POST',

           url:postURL,

           data:{_token: '{{csrf_token()}}', getMesg: getMesg, chooseUser: chooseUser.join(",")},

           success:function(data){

           }

         });

        });

        $('#btn_delete').click(function(){

          var deleteURL = "{{route('deleteusers') }}";

          var chooseUser = [];

          $.each($("input[name='checkUser']:checked"), function(){

            chooseUser.push($(this).val());

          });

          if($("#ckbCheckAll").prop('checked') == true){

            confirm("Are you sure you want to delete this?");

            $.ajax({ 

              type:'POST',

              url:deleteURL,

              data: {_token: '{{csrf_token()}}', chooseUser: chooseUser.join(","),'allRecord':'all'},

              success:function(data){

                window.location.reload();

              }

            });

          } else {

            confirm("Are you sure you want to delete this record?");

            $.ajax({ 

              type:'POST',

              url:deleteURL,

              data: {_token: '{{csrf_token()}}', chooseUser: chooseUser.join(","),'allRecord':'none'},

              success:function(data){

                window.location.reload();

              }

            });
          }

        });

        $('.opendropdown').click(function(e){
          $('.dropdown-box').toggleClass("open");
          e.preventDefault();
        });

        $.noConflict();
        $('#dataTable').DataTable();
      });
    </script>
    @endsection
