@extends('admin.layout.master')

@section('content')
  <main class="app-content" id="app">
     <div class="app-title">
        <div>
           <h1>Time Setups</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">

          <div class="tile">
            <div class="row">

              <div class="col-md-12">

                <form class="form-horizontal" action="{{route('admin.flashsale.updatetimes')}}" method="post" role="form">
                   {{csrf_field()}}
                   <div class="form-body">
                     <div class="form-group">
                        <label class="col-md-12 "><strong style="text-transform: uppercase;">Time Intervals in a day</strong></label>
                        @foreach ($intervals as $key => $interval)
                          <div id="interval{{$interval->id}}" class="col-md-12 mt-2">
                            @php
                              if(substr($interval->end_time, 0, 2) == "24") {
                                $end_time = substr_replace($interval->end_time,"00",0,2);
                              } else {
                                $end_time = $interval->end_time;
                              }
                            @endphp
                             <input id="starttime{{$interval->id}}" name="start_time[]" type="text" value="{{$interval->start_time}}" @focus="initStartTp({{$interval->id}})" autocomplete="off"> - <input id="endtime{{$interval->id}}" name="end_time[]" type="text" @focus="initEndTp({{$interval->id}})" value="{{$end_time}}" autocomplete="off">
                             <button class="btn btn-danger btn-sm" type="button" @click="removeinterval({{$interval->id}})"><i class="fa fa-times"></i></button>
                          </div>
                        @endforeach
                        <div :id="'interval'+interval.id" class="col-md-12 mt-2" v-for="interval in intervals">
                           <input :id="'starttime'+interval.id" name="start_time[]" type="text" value="" @focus="initStartTp(interval.id)" autocomplete="off"> - <input :id="'endtime'+interval.id" name="end_time[]" type="text" value="" @focus="initEndTp(interval.id)" autocomplete="off">
                           <button class="btn btn-danger btn-sm" type="button" @click="removeinterval(interval.id)"><i class="fa fa-times"></i></button>
                        </div>
                        <div class="col-md-12 mt-2">
                          <button type="button" class="btn btn-primary" @click="incrinterval"><i class="fa fa-plus"></i> Add time interval</button>
                        </div>
                     </div>
                      <div class="form-group">
                         <div class="col-md-12">
                            <button type="submit" class="btn btn-info btn-block btn-lg">UPDATE</button>
                         </div>
                      </div>
                   </div>
                </form>
              </div>

            </div>
          </div>
        </div>
     </div>
  </main>
@endsection

@push('scripts')
<script>


var app = new Vue({
  el: '#app',
  data: {
    id: 0,
    intervals: [],
    lastid: parseInt('{{$lastid}}')
  },
  created() {
    console.log(this.lastid);
  },
  methods: {
    incrinterval() {
      let id = ++this.id;
      var newid = this.lastid+id;
      console.log(newid);
      this.intervals.push({'id': newid})
    },
    removeinterval(id) {
      $("#interval"+id).remove();
    },
    initStartTp(id) {
      console.log(id);
      var start = $('#starttime'+id);
      start.datetimepicker({
        datepicker:false,
        format:'H:i'
      });
      start.datetimepicker('show');
    },
    initEndTp(id) {
      console.log(id);
      var end = $('#endtime'+id);
      end.datetimepicker({
        datepicker:false,
        format:'H:i'
      });
      end.datetimepicker('show');
    },
  }
})
</script>
@endpush
