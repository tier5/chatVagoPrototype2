@extends('layouts.app')
@section('content')
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Home</a>
        </li>
        <li class="breadcrumb-item active">Boardcast</li>
      </ol>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Boardcast</div>
        <div class="card-body">
          <h4 class="row justify-content-center">API LINK : https://chatvago.tier5-development.us/{{ Auth::user()->id }}/insertUser?psid={{($pageScopeUserId == '') ? 'PSID' : $pageScopeUserId}}</h4>
            <div col-sm-12 col-md-6>
              <button type="button" class="btn btn-primary px-5" data-toggle="modal" data-target="#boardcastModal" style="float: left; margin-top: 20px; margin-bottom: 20px;"">Send Message</button>
            </div>
            <div col-sm-12 col-md-6>
              <button type="button" class="btn btn-danger px-5" id="deleteUser" name="deleteUser" style="float: right; margin-top: 20px; margin-bottom: 20px;"">Delete</button>
            </div>
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th><input type="checkbox" name="ckbCheckAll" id="ckbCheckAll"></th>
                      <th>Profile Picture</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($getBoardCastUser as $key => $getBroadcastUser)
                    <tr>
                      <td><input type="checkbox" name="checkUser" id="checkUser" value="{{$getBroadcastUser->fb_id}}"></td>
                      <td><img src="{{$getBroadcastUser->profile_picture}}" width="80" height="80"></th>
                      <td>{{$getBroadcastUser->first_name}}</td>
                      <td>{{$getBroadcastUser->last_name}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
        <div class="card-footer small text-muted"></div>
      </div>
      <div class="modal fade" id="boardcastModal" tabindex="-1" role="dialog" aria-labelledby="boardcastModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Message</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body"><textarea class="form-control" rows="10" id="send_message_text" name="send_message_text"></textarea></div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" id="sendMessage">{{ __('Send') }}</button>
            </div>
            <div class="loader" id="modalLoader" style="display: none;"></div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Error</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Please select an user.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>      
    </div>
    <script type="text/javascript">

      $('#ckbCheckAll').click(function(){
        if (this.checked == false) {
          $('#checkUser:checked').attr('checked', false);
        } else {
          $('#checkUser:not(:checked)').attr('checked', true);
        }
      });
      
      $('#sendMessage').click(function(){

        var sendURL = '{{route('boardcast')}}';
        var selectedUser = [];
        var getMessage = $('#send_message_text').val();
        $.each($("input[name='checkUser']:checked"), function(){
          selectedUser.push($(this).val());
        });

        if(selectedUser.length == 0){

          $('#boardcastModal').modal('hide');
          $('#errorModal').modal('show');

          return false;

        }
        $('#modalLoader').css('display', 'block');
        $.ajax({

          type:'POST',
          url: sendURL,
          data:{_token: '{{csrf_token()}}', getMessage: getMessage, selectedUser: selectedUser.join(",")},
          success: function(response){
            $('#modalLoader').css('display', 'none');
          }

        });
      });

      $('#deleteUser').click(function(){
        var deleteURL = "{{route('deleteusers') }}";
        var selectedUser = [];
        $.each($("input[name='checkUser']:checked"), function(){
          selectedUser.push($(this).val());
        });

        if($("#ckbCheckAll").prop('checked') == true){

          confirm("Are you sure you want to delete this?");

          $.ajax({

            type:'POST',
            url:deleteURL,
            data: {_token: '{{csrf_token()}}', selectedUser: selectedUser.join(","),'allRecord':'all'},
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
    </script>
@endsection