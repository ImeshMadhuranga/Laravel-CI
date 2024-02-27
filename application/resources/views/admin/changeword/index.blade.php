@extends('admin.layouts.master')
@section('title', 'All Language - Admin')
@section('maincontent')
<?php
$data['heading'] = 'All Language';
$data['title'] = 'All Language';
?>
@include('admin.layouts.topbar',$data)
<div class="contentbar">
    <div class="row">
        @if ($errors->any())  
            <div class="alert alert-danger" role="alert">
                @foreach($errors->all() as $error)     
                <p>{{ $error}}<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="color:red;">&times;</span></button></p>
                @endforeach  
            </div>
        @endif
        <!-- row started -->
        <div class="col-lg-12">
            <div class="card dashboard-card m-b-30">
                <!-- Card header will display you the heading -->
                <div class="card-header">
                    <h5 class="card-box">{{ __('All Language') }}</h5>
                </div>
                <!-- card body started -->
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- table to display faq start -->
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                                <th>#</th>
                                <th>{{ __('Language Traslation') }}</th>
                                <th>{{ __('Action')}}</th>
                            </thead>

                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td><b> ar.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/ar.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td><b> bn.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/bn.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td><b> de.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/de.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td><b> en.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/en.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">5</th>
                                    <td><b> es.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/es.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">6</th>
                                    <td><b> et.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/et.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">7</th>
                                    <td><b> fa.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/fa.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">8</th>
                                    <td><b> fr.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/fr.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">9</th>
                                    <td><b> hi.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/hi.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">10</th>
                                    <td><b> it.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/it.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">11</th>
                                    <td><b> ko.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/ko.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">12</th>
                                    <td><b> nl.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/nl.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">13</th>
                                    <td><b> pl.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/pl.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">14</th>
                                    <td><b> pt-br.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/pt-br.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">15</th>
                                    <td><b> pt.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/pt.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">16</th>
                                    <td><b> ro.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/ro.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">17</th>
                                    <td><b> ru.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/ru.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">18</th>
                                    <td><b> tr.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/tr.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">19</th>
                                    <td><b> ur.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/ur.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">20</th>
                                    <td><b> zh-CN.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/zh-CN.json')}}">{{ __("Edit")}}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">21</th>
                                    <td><b> zh-TW.json </b></td>
                                    <td><a class="btn btn-primary" href="{{url('change/json/zh-TW.json')}}">{{ __("Edit")}}</a></td>
                                </tr> 
                            </tbody>
                        </table>                  
                        <!-- table to display faq data end -->                
                    </div><!-- table-responsive div end -->
                </div><!-- card body end -->
                
            </div><!-- col end -->
        </div>
    </div>
</div><!-- row end -->
@endsection
<!-- main content section ended -->
<!-- This section will contain javacsript start -->
<!-- This section will contain javacsript end -->