var global_access_token = '';
window.fbAsyncInit = function() {
  FB.init({
        appId      : appID,
        xfbml      : true,
        version    : 'v2.12'
    });

    FB.getLoginStatus(function(o) { 
      if (!o && o.status) return;
      if (o.status == 'connected') {
         console.log("Connected");
         $('.step:eq(1)').removeClass('disableBack');
         $('.step:eq(1)').trigger('click');
         facebookUID = o.authResponse.userID;
        getFbUserPageData(facebookUID);
      } else if (o.status == 'not_authorized') {
        console.log("Not Autherize");
      } else {
         console.log("USER NOT CURRENTLY LOGGED IN TO FACEBOOK");
      }
   });
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

$(document).on('click', '.pageLi', function () {
    $('.pageLi').removeClass('selected');
    $(this).addClass('selected');
    $('.opendropdown').trigger('click');
    page_id = $(this).attr('data-page-id');
    page_access_token  = $(this).attr('data-access-token');
    //window,location.href = '/'+ page_id;
    if(page_access_token){
      $.ajax({
        type: "post",
        url: "store_page_access_token_data",
        data: {"_token": genCSRF(),page_access_token: page_access_token, page_id : page_id},
      success: function(response) {
        window,location.href ='/analytics';
      }
      });
    }
  });

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
      saveFacebookUser(response);

      FB.api('/me/accounts', function(response) {
        console.log('Successfully retrieved pages', response);
        saveFbUserPageData(response);
      });
  });
};

  //store user info

  function saveFacebookUser(response){
    $.ajax({
        type: "post",
        url: "store_user_data",
        data: {"_token": genCSRF(),response: response},
        success: function() {
        }
      });
  }

  function saveFbUserPageData(response){
    $.ajax({
        type: "post",
        url: "store_page_data",
        data: {"_token": genCSRF(),response: response},
        success: function(dataType) {
          if(dataType != 'error'){
            getFbUserPageData(dataType);
            $('.loader-div').hide();
            $('.transparent').hide();
          }
        }
      });
  }

  function getFbUserPageData(facebookUID, ){
    $.ajax({
        type: "GET",
        url: "get_page_data",
        data: {"_token": genCSRF(),facebookID : facebookUID},
        success: function(response) {

          var appendHtml  = '';
          if(parseInt(response.length)>0) {
            for (var i = 0, len = response.length; i < len; i++)
            {
              appendHtml = appendHtml + '<li data-access-token="'+response[i].page_access_token+'" data-page-id="'+response[i].fb_page_id+'" class="pageLi"><span>'+response[i].fb_page_name+'</span></</li>';
            }
          } else  {
            appendHtml = '<li>No Page Found</li>';
          }
          $('.dropdown-box > ul').html(appendHtml);
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



