@extends('backpack::layout')

@push('after_styles')
    <style>
        .nav-tabs-custom {
            box-shadow: none;
        }
        .nav-tabs-custom > .nav-tabs.nav-stacked > li {
            margin-right: 0;
        }

        .tab-pane .form-group h1:first-child,
        .tab-pane .form-group h2:first-child,
        .tab-pane .form-group h3:first-child {
            margin-top: 0;
        }
    </style>
@endpush

@section('header')
	<section class="content-header">
	  <h1>
	    <span class="text-capitalize">{{ $title }}</span>
	    <small>{{ trans('backpack::crud.all') }} <span>{{ $title }}</span></small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($route) }}" class="text-capitalize">{{ $title }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="col-md-12">
      <div class="box">

        <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">

          <div class="tab-container col-xs-3 m-t-10">

              <div class="nav-tabs-custom" id="form_tabs">
                  <ul class="nav nav-stacked nav-pills" role="tablist">
                      @foreach ($configFiles as $k => $tab)
                          <li role="presentation" class="{{$k == 0 ? 'active' : ''}}">
                              <a href="#tab_{{ str_slug($tab, "") }}" tabName="{{ $tab }}" aria-controls="tab_{{ str_slug($tab, "") }}" role="tab" data-toggle="tab">{{ str_replace('.', '/', $tab) }}</a>
                          </li>
                      @endforeach
                  </ul>
              </div>

          </div>

          <div class="tab-content col-md-9 m-t-10">
            <form method="post" action="">
            {!! csrf_field() !!}
              <div class="tab-content col-md-9 m-t-10">
                @foreach ($configFiles as $k => $tab)
                <div role="tabpanel" class="tab-pane{{$k == 0 ? ' active' : ''}}" id="tab_{{ str_slug($tab, "") }}">

                </div>
                @endforeach

                @if($canUpdate)
                  <input type="submit" class="btn btn-primary m-t-15 pull-right" value="Save Settings" />
                @endif
              </div>
            </form>
          </div>
      </div><!-- /.box -->
    </div>

  </div>

@endsection

@push('after_scripts')
  <script>
    getFields("app");
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).attr("tabName") // activated tab
      this.target = target;
      $("#tab_" + target).empty();
      getFields(target);
    });

    function getFields(fileName) {

      jsonURL = '{!! backpack_url('setting/system/search/') !!}/' + fileName;

      $.getJSON(jsonURL , function( data ) {
        $.each( data, function( key, val) {
          fileName = fileName.replace('.', '');
          $("<div class='form-group col-xs-12'>").appendTo( "#tab_" + fileName);
          $("<label>" + key.replace('.', ' ').replace('.', ' ').replace('_', ' ').replace('_', ' ').replace('_', ' ') + "</label>").appendTo( "#tab_" + fileName).css('textTransform', 'capitalize');
          var input = $("<input type='text' class='form-control'>" ).attr( "name", key ).attr("value", val);
          @if(!$canUpdate)
            var.input.attr('disabled', 'disabled');
          @endif
          input.appendTo( "#tab_" + fileName);
          $("</div>").appendTo( "#tab_" + fileName);
        });
      });
    }

  </script>
@endpush
