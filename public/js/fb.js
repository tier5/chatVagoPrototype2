var global_access_token = '';
window.fbAsyncInit = function() {
    FB.init({
        appId      : appID,
        xfbml      : true,
        version    : 'v2.12'
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
    //window,location.href = '/'+ page_id;
    window,location.href ='/analytics/' + page_id;
     
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
      var pages       = response.data;
      var appendHtml  = '';
      $('.step:eq(1)').removeClass('disableBack');
      if(parseInt(response.data.length)>0)
      {
        var geturl = '';

        for (var i = 0, len = response.data.length; i < len; i++)
        {
          
          appendHtml = appendHtml + '<li data-access-token="'+response.data[i].access_token+'" data-page-id="'+response.data[i].id+'" class="pageLi"><span>'+response.data[i].name+'</span></</li>'; 
        }

      }
      else
      {
          appendHtml = '<li>No Page Found</li>';
      }
      $('.dropdown-box > ul').html(appendHtml);
      $('.loader-div').hide();
      $('.transparent').hide();
    });
  //}, 1000);
}, {scope:'manage_pages'});
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



