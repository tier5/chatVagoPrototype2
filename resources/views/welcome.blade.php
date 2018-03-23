@extends('partials.layouts')

@section('body')
<script src="/js/fb.js"></script>       
    <div class="container">
        <div class="content">
            <div class="title">Chat Vago</div>
            <!-- Modal -->
            <!-- Facebook Leads Modal -->
            <div id="facebookleadModal" class="modal fade" role="dialog">
                   <div class="modal-dialog">
                       <div class="modal-content">
                           <div class="modal-header">
                               <button id="head_close_areaSearch" type="button" class="close" data-dismiss="modal">&times;</button>
                               <h4 class="modal-title">Facebook</h4>
                           </div>
                           <div class="modal-body">
                               @include('facebookDetails.facebookPages')
                           </div>
                           <div class="modal-footer">
                               <button id="tail_close_areaSearch" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                           </div>
                           <div class="loader-div" style="display:none;"></div>
                           <div class="transparent" style="display:none;"></div>
                       </div>
                   </div>
            </div>
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#facebookleadModal">Facebook</button>
            
        </div>
    </div>
@endsection