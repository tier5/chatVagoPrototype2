@extends('layouts.app')
@section('content')
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Home</a>
        </li>
        <li class="breadcrumb-item active">Analytics</li>
      </ol>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-area-chart"></i> Analytics</div>
        <div class="card-body">
              <div class="form-group row">
                <label for="name" class="col-md-11 col-form-label text-md-right">{{ __('Date Rang') }}</label>
                <div class="float-lg-right">
                  <select id="dateRange" name="dateRange" class="form-control">
                    <option value="1day" @if($oneWeekTime == '1day') selected="selected" @endif>1 Day</option>
                    <option value="7days" @if($oneWeekTime == '7days') selected="selected" @endif>7 Days</option>
                    <option value="1month" @if($oneWeekTime == '1month') selected="selected" @endif>1 Month</option>
                  </select>
                </div>
              </div>
              <div class="loader" id="modalLoader" style="display: none;"></div>
              <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Analytics</th>
                      <th>Value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td scope="row">page_messages_reported_conversations_by_report_type_unique</td>
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
                            <td scope="row">{{date('Y-m-d',strtotime($report_type_unique['end_time']))}}</td>
                            <td>{{(array_key_exists('spam', $report_type_unique['value']) == '') ? 0 : $report_type_unique['value']['spam'] }}</td>
                            <td>{{(array_key_exists('inappropriate', $report_type_unique['value']) == '') ? 0 : $report_type_unique['value']['inappropriate'] }}</td>
                            <td>{{(array_key_exists('other', $report_type_unique['value']) == '') ? 0 : $report_type_unique['value']['other'] }}</td>
                            <td>{{$report_type_unique['value']['spam'] + $report_type_unique['value']['inappropriate'] + $report_type_unique['value']['other']}}</td>
                          @php($report_type_unique_total += $report_type_unique['value']['spam'] + $report_type_unique['value']['inappropriate'] + $report_type_unique['value']['other'])
                            </td>
                          </tr>
                          @endforeach
                          <tr>
                            <th scope="row" colspan="2">Total</th>
                            <th scope="row" colspan="3">{{$report_type_unique_total}}</th>
                          </tr>
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
                    <td scope="row">page_messages_new_conversations_unique</td>
                    <td>
                      <table class="table table-bordered">
                        <tbody>
                          <thead>
                            <tr>
                              <th scope="col">Date</th>
                              <th scope="col">Value</th>
                            </tr>
                          </thead>
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
                    </tr>
                    <tr>
                      <td scope="row">page_messages_open_conversations_unique</td>
                      <td>
                        <table class="table table-bordered">
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
                                <td scope="row">{{date('Y-m-d',strtotime($open_conversations_unique['end_time']))}}</td>
                                <td>{{(array_key_exists('value', $open_conversations_unique) == '') ? 0 : $open_conversations_unique['value'] }}</td>
                              @php($open_conversations_unique_total += (array_key_exists('value', $open_conversations_unique) == '') ? 0 : $open_conversations_unique['value'])
                              </tr>
                              @endforeach
                              <tr>
                                <th scope="row">Total</th>
                                <th scope="row">{{$open_conversations_unique_total}}
                                </th>
                              </tr>
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
                      <td scope="row">page_messages_blocked_conversations_unique</td>
                      <td>
                        <table class="table table-bordered">
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
                                  <td scope="row">{{date('Y-m-d',strtotime($blocked_conversations_unique['end_time']))}}</td>
                                  <td>{{(array_key_exists('value', $blocked_conversations_unique) == '') ? 0 : $blocked_conversations_unique['value'] }}</td>
                                @php($blocked_conversations_unique_total += (array_key_exists('value', $blocked_conversations_unique) == '') ? 0 : $blocked_conversations_unique['value'])
                              </tr>
                              @endforeach
                              <tr>
                                <th scope="row" >Total</th>
                                <th scope="row" >{{$blocked_conversations_unique_total}}</th>
                              </tr>
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
                      <td scope="row">page_messages_active_threads_unique</td>
                      <td>
                        <table class="table table-bordered">
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
                                <td scope="row">{{date('Y-m-d',strtotime($active_threads_unique['end_time']))}}</td>
                                <td>{{(array_key_exists('value', $active_threads_unique) == '') ? 0 : $active_threads_unique['value'] }}</td>
                                @php($active_threads_unique_total += (array_key_exists('value', $active_threads_unique) == '') ? 0 : $active_threads_unique['value'])
                              </tr>
                              @endforeach
                              <tr>
                                <th scope="row" >Total</th>
                                <th scope="row">{{$active_threads_unique_total}}</th>
                              </tr>
                              @else
                              <tr>
                                <td colspan="5" style="text-align:center;">No Record Found</td>
                              </tr>
                            @endif
                          </tbody>
                        </table>
                      </td>
                    </tr>
                </tbody>
                </table>
              </div>
            </div>
        <div class="card-footer small text-muted"></div>
      </div>      
    </div>
    <script type="text/javascript">

      $(document).ready(function(){

        $('#dateRange').change(function() {

        var analyticURL = "{{route('analytics') }}";
        var dateRange = $('#dateRange').val();
        $('#modalLoader').css('display', 'block');
          $.ajax({
            type:'POST',
            url:analyticURL,
            data: {_token: '{{csrf_token()}}', dateRange: dateRange},
            success:function(data){
            $('#modalLoader').css('display', 'hide');
             window.location.reload();
            }
          });
        });

      });
    </script>
@endsection