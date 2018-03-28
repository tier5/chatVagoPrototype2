var global_access_token = '';
window.fbAsyncInit = function() {
    FB.init({
        appId      : appID,
        xfbml      : true,
        version    : 'v2.9'
    });
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//function subscribeApp(page_id, page_access_token)
$(document).on('click', '.pageLi', function () {
    $('.pageLi').removeClass('selected');
    $(this).addClass('selected');
    $('.opendropdown').trigger('click');
    page_id = $(this).attr('data-page-id');
    page_access_token  = $(this).attr('data-access-token');
    console.log('Subscribing page to app! ' + page_id);
     $('.loader-div').show();
     $('.transparent').show();
    FB.api(
      '/' + page_id + '/subscribed_apps',
      'post',
      {access_token: page_access_token},
      function(response) {
      console.log('Successfully subscribed page', response);
      getLeadForms(page_id, page_access_token);
    });
});

function getLeadForms(page_id, page_access_token)
{
  //alert(page_id);
  $.ajax({
      type: "post",
      url: "get_lead_forms",
      data: {"_token": genCSRF(),page_id: page_id,access_token: page_access_token},
      success: function(dataString) {
          $('#formList').html(dataString);
          token_arr = global_access_token.split('--');
          $.each(token_arr, function() {
              var subArr = this.split('@');
              if( page_id == subArr[0])
              {
                  $('#syncFormLeads').attr('data-access-token',subArr[1]);
                  $('#syncFormLeads').attr('data-page-id',subArr[0]);
              }
          });
          console.log('**mentor_list div updated via ajax.**');
          $('.loader-div').hide();
          $('.transparent').hide();
      }
    });
}

// Only works after `FB.init` is called
function facebookLogin()
{
    window.fbAsyncInit();
    FB.login(function(response){
  console.log('Successfully logged in', response);
  if(response.status=='connected'){
     $('.step:eq(1)').removeClass('disableBack');
     $('.step:eq(1)').trigger('click');
  }
  $('.loader-div').show();
  $('.transparent').show();
  //setTimeout(function(){
    storeUserData(response);
    FB.api('/me/accounts', function(response) {
      console.log('Successfully retrieved pages', response);
      storeUserPageData(response);
      var pages       = response.data;
      var appendHtml  = '';
      if(parseInt(pages.length)>0)
      {
          var leadCntArr = '';
          $.ajax({
             type: "post",
             async:false,
             url: "getformcountbypage",
             data: {"_token": genCSRF(),pages: pages},
             success: function(dataString) {
                 leadCntArr = dataString;
                 for (var i = 0, len = pages.length; i < len; i++)
                 {
                    var page = pages[i];
                    if(parseInt(leadCntArr[page.id]['form_count']) > 0 )
                    {
                      if(global_access_token == '')
                      {
                        global_access_token = page.id+'@'+page.access_token;
                      }
                      else
                      {
                        global_access_token = global_access_token+'--'+page.id+'@'+page.access_token;
                      }

                      var pageImageHtml = '';

                      if(leadCntArr[page.id]['image_path'] != '')
                      {
                        pageImageHtml = '<img src="'+leadCntArr[page.id]['image_path']+'">';
                      }
                      else
                      {
                            pageImageHtml = '<div class="user-name-icon" style="background: '+getRandomColor()+'; color:'+getRandomColor()+';">'+page.name.substring(0,1)+'</div>';
                      }
                      appendHtml = appendHtml + '<li data-access-token="'+page.access_token+'" data-page-id="'+page.id+'" class="pageLi">'+pageImageHtml+'<span>'+page.name+'</span><span class="count">'+leadCntArr[page.id]['form_count']+'</span></li>';
                    }
                 } //loopends
             }
          });
      }
      else
      {
          appendHtml = '<li>No Page Found</li>';
      }
      $('.dropdown-box > ul').html(appendHtml);
      $('.opendropdown').trigger('click');
      $('.loader-div').hide();
      $('.transparent').hide();
    });
  //}, 1000);
}, {scope: 'manage_pages'});
};

  //store user info

  function storeUserData(response){
    $.ajax({
        type: "post",
        url: "store_user_data",
        data: {"_token": genCSRF(),response: response},
        success: function() {
            //$('#formsList').html(dataString);
            console.log('**mentor_list div updated via ajax.**');
        }
      });
  }

  function storeUserPageData(response){
    $.ajax({
        type: "post",
        url: "store_user_page_data",
        data: {"_token": genCSRF(),response: response},
        success: function() {
            //$('#formsList').html(dataString);
            console.log('**mentor_list div updated via ajax.**');
        }
      });
  }


    function getPagePictures(pageId) {
        var pageData ='';
        FB.api(
        "/"+pageId+"/picture",
        function (response) {
          if (response && !response.error) {
                pageData= response.data;
                //alert(JSON.stringify(response));
                //console.log(pageData.url);
            }
        });
    }

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId="+appID;
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function getRandomColor()
    {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
          color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    $(document).on('click', '.directoryModal', function () {
      var formId = $(this).attr('id');
      $('#hidden_form_id').val(formId);
		  $('#syncFormLeads').attr('data-form-id',formId);
		  $('.step:eq(2)').removeClass('disableBack');
		  $('.step:eq(2)').trigger('click');
		  $('.step:eq(1)').addClass('disableBack');
	});

    $(document).on('click', '.viewList', function () {
		var redirectUrl = directoryUrl+$(this).attr('id');
		window.open(redirectUrl, '_blank');
	});

    $(document).on('click', '#create_list', function () {
        //setTimeout(function(){
        var selected_campaigns = new Array();
        $("#createDirectory").find("input[name='campaign_assign[]']:checked").each(function(){
          selected_campaigns.push($(this).val());
        });


        var directory_name = $("#createDirectory").find('input[name=directory_name]').val();
        var directory_desc = $("#directory_desc_save_directory").val();
        var form_id         = $("#createDirectory").find('input[name=form_id]').val();
        if($('#syncFormLeads').is(":checked")){
          var global_access_token = $('#syncFormLeads').attr('data-access-token');
          var pageId 				= $('#syncFormLeads').attr('data-page-id');
          var sync = 1;
        }else{
          var sync = 0;
          var global_access_token = $('#syncFormLeads').attr('data-access-token');
          var pageId 				= $('#syncFormLeads').attr('data-page-id');
        }
        if(directory_name == '')
        {
            $("#createDirectory").find('input[name=directory_name]').css('border','solid red');
            return false;
        }
        $('.loader-div').show();
        $('.transparent').show();
        $.ajax({
            type: "post",
            url: directorySaveURL,
            data: {"_token": genCSRF(),directory_name: directory_name
            ,directory_desc:directory_desc
            ,form_id:form_id
            ,sync:sync
            ,global_access_token:global_access_token
            ,pageId:pageId
            ,campaigns:selected_campaigns},
            success: function(dataString) {
                if(dataString == 'not logged'){
                    $('.loader-div').hide();
                    $('.transparent').hide();
                    $('#create_list').prop('disabled', false);
                    sweetAlert("Oops...", 'Some error occured', "error");
                }else if(dataString == 'already exsist'){
                    $('.loader-div').hide();
                    $('.transparent').hide();
                    $('#create_list').prop('disabled', false);
                    sweetAlert("Oops...", 'Phone number already exsist', "error");
                }else{
                     $('.loader-div').hide();
                     $('.transparent').hide();
                     $('#facebookleadModal').modal('toggle');
                     if(sync ==1 ){
                       sweetAlert("Success...", 'List Created,sync in progress', "success");
                     }else{
                       sweetAlert("Success...", 'List Created', "success");
                     }
                }
            }
              // var redirectUrl = directoryUrl;
              // window.location.href = redirectUrl;
          });
});
