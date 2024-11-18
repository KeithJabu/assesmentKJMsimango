@extends('app')

@section('content')
    <div class="page-inner offset-lg-2">
        <div class="page-header">
            <h3 class="fw-bold mb-3"> Assessment:Job View   </h3>

            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#">
                        <i class="fa fa-home-alt"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="fa fa-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">jobs</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Job List and Response</h4>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                            @foreach ($tab_list as $tab)
                                <li class="nav-item">
                                    <a class="nav-link @if ($tab['template'] == $tab_list->first()['template']) active @endif" id="{{ $tab['template'] }}-tab" data-bs-toggle="pill" href="#{{ $tab['template'] }}" role="tab" aria-controls="{{ $tab['template'] }}" aria-selected="true">{{ $tab['title'] }}</a>
                                </li>
                            @endforeach

                        </ul>

                        <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                            @foreach($tab_list as $tab)
                                <div class="tab-pane fade @if ($tab['template'] == $tab_list->first()['template']) active show @endif" id="{{ $tab['template'] }}" role="tabpanel" aria-labelledby="{{ $tab['template'] }}-tab">
                                    @include('layouts.tabs.' . $tab['template'])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<style>
    .main-panel {
        float: left !important;
    }
</style>
