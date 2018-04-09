@extends('layouts.app')
@section('content')
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Home</a>
        </li>
        <li class="breadcrumb-item active">Messenger Code</li>
      </ol>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-area-chart"></i> Generate Code</div>
        <div class="card-body">
           <div class="alert alert-danger" id="showErrorMessage" style="display: none;"></div>
          <div class="form-group">
            <label for="name">Data Ref</label>
            <input type="text" name="messenger_ref" id="messenger_ref" class="form-control">
          </div>
          <div class="form-group">
            <label for="name">Image Size Range (Supported range: 100-2000 px)</label>
            <input type="text" name="image_size" id="image_size" class="form-control">
          </div>
          <div class="col-md-8 offset-md-5">
          <button class="btn btn-primary" id="generateMessagerImage">
                Generate
            </button>
          </div>
          <div class="form-group">
           &nbsp;
          </div>
          <div id="showGenImage" style="display: none;">
            <div class="col-md-8 offset-md-4">
            <!-- <img src="https://scontent.xx.fbcdn.net/v/t39.8917-6/29631242_1430733653716114_6444764269220200448_n.png?_nc_cat=0&oh=298599fa08e1426de03f5b3db472b60f&oe=5B342EF4" id="messengerImage" class="img-responsive center-block" style="width: 300px; height: 300px; auto" /> -->

            <img src="" id="messengerImage" class="img-responsive center-block" style="width: 300px; height: 300px; auto" />
          </div>
          <div class="form-group">
           &nbsp;
          </div>
          <div class="col-md-8 offset-md-5">
          <a href="" class="btn btn-primary" name="code.jpg" download="code.jpg" id="downloadImg">Download</a>
          </div>
          </div>
          <div class="loader" id="modalLoader" style="display: none;"></div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $(document).ready(function(){

        $('#generateMessagerImage').click(function(){

          var genImgURL = "{{route('messenger_image')}}";
          var messenger_ref = $('#messenger_ref').val();
          var image_size = $('#image_size').val();

          if(messenger_ref == ''){
            $('#showErrorMessage').css('display', 'block');
            $('#showErrorMessage').text('Please enter data ref.');
          } else if (image_size != '' && image_size < 100 || image_size > 2000){
            $('#showErrorMessage').text('Supported image size range: 100-2000 px.');
          } else {
            $('#modalLoader'). css('display', 'block');
            $.ajax({
              type: 'POST',
              url: genImgURL,
              data: {_token: '{{csrf_token()}}', messenger_ref: messenger_ref, image_size: image_size},
              success:function(response){
                var jsonData = JSON.parse(response);

                console.log(response);

                var getImageURI = jsonData.uri;
                $('#messengerImage').attr('src',getImageURI);
                $('#downloadImg').attr('href',getImageURI);
                $('#showGenImage'). css('display', 'block');
                $('#modalLoader'). css('display', 'none');
              }
            });
          }
        });
      });
    </script>
@endsection