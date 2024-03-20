@extends('layouts.app')

@php

    $translate=[

    'fa'=>[
    'title'=>'اطلاعات پرداخت',
    'success'=>'پرداخت با موفقیت انجام شد',
    'danger'=>'مشکلی در پرداخت پیش آمد',
    'time'=>Morilog\Jalali\Jalalian::now(new DateTimeZone('Asia/Tehran'))->format('%A, %d %B %Y ⏰ H:i'),
    'pay_id'=>'رسید پرداخت'  ,
    'pay_time'=>'زمان پرداخت'  ,
    'amount'=>'مبلغ'  ,
    'currency'=>'ریال'  ,
    'type'=>'نوع محصول'  ,
    'open_app'=>'برنامه را مجدد باز نمایید'  ,
    'return_app'=>'لطفا اپلیکیشن را مجدد باز نمایید'  ,
    ],
    'en'=>[
       'title'=>'Payment Info',
       'success'=>'Payment Success',
         'danger'=>'Payment Failed',
        'time'=>   Morilog\Jalali\Jalalian::now()->format('%A, %d %B %Y ⏰ H:i'),
        'pay_id'=>'Purchase Code'  ,
         'pay_time'=>'Pay Time',
       'amount'=>'Price'  ,
         'currency'=>''  ,
         'type'=>'Purchase Type',
          'open_app'=>'Return To Application'  ,
           'return_app'=>'You Can Return To Application'  ,
    ]
    ];

            $link=isset($link)?$link:null;
            $status=isset($status) && $status=='success' ?$status:'danger';
            $pay_id=isset($pay_id)?$pay_id:'_';
            $amount=isset($amount)?$amount:'_';
            $type=isset($type)?$type:'_';
            $lang=isset($lang)?$lang:'en';


@endphp

@section('title')
    {{ $translate[$lang]['title']}}
@stop
@section('content')

    <div class="container position-relative {{$lang=='fa'?'text-right':''}}">
        {{--<div class="position-absolute w-100 h-100 top-0 start-0 end-0 bottom-0 opacity-30"--}}
        {{--style="background: url('{{asset('img/texture.jpg')}}'); background-repeat:repeat;background-size: cover;z-index: 0">--}}
        {{--</div>--}}
        <div class="row  justify-content-center">
            <div class="col-md-6 col-lg-4 col-sm-8 col-ms-10 mx-auto">
                <div class="card my-5 bg-light">
                    <div
                        class="card-header text-center text-lg text-white font-weight-bold bg-{{$status}}"> {{$translate[$lang][$status]}}

                    </div>

                    <div class="card-body text-primary">
                        <form method="POST">


                            <div class="form-group  ">
                                <span
                                    class="col-12 col-form-label text-md-right text-dark">{{$translate[$lang]['pay_time']}}</span>

                                <div class="col-12">
                                    <div class="text-center font-weight-bold">{{ $translate[$lang]['time']}}</div>
                                </div>

                                <hr class="my-1"/>
                            </div>
                            <div class="form-group  ">
                                <span
                                    class="col-12 col-form-label text-md-right text-dark">{{$translate[$lang]['type']}}</span>

                                <div class="col-12">
                                    <div class="text-center font-weight-bold">{{ $type }}</div>
                                </div>

                                <hr class="my-1"/>
                            </div>

                            <div class="form-group  ">
                                <span
                                    class="col-12 col-form-label text-md-right text-dark">{{$translate[$lang]['amount']}}</span>

                                <div class="col-12">
                                    <div
                                        class="text-center font-weight-bold">{{"$amount " .$translate[$lang]['currency'] }}</div>
                                </div>

                                <hr class="my-1"/>
                            </div>
                            <div class="form-group  ">
                                <span
                                    class="col-12 col-form-label text-md-right text-dark">{{$translate[$lang]['pay_id']}}</span>

                                <div class="col-12">
                                    <div class="text-center font-weight-bold">{{ $pay_id }}</div>
                                </div>

                                <hr class="my-1"/>
                            </div>

                            @if($link)
                                <div class="form-group    row">
                                    <div class="col-12 my-2  ">
                                        <a href="{{$link}}" type="submit"
                                           class="d-block btn btn-primary text-white   btn-block py-3    font-weight-bold ">
                                            {{$translate[$lang]['open_app']}}
                                        </a>

                                    </div>

                                </div>

                            @else
                                <div class="form-group text-center   row">
                                    <div class="col-sm-12 my-2  ">
                                        <div
                                            class="text-primary py-3    font-weight-bold ">
                                            {{$translate[$lang]['return_app']}}
                                        </div>

                                    </div>

                                </div>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
