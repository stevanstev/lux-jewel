@extends('template/container', ['show' => false])

@section('title')
    Notifications
@endsection

@section('section')
    @if(count($notif_data) == 0)
        @include('template/empty_page', 
            [
                'target' => '/', 
                'button_text' => 'Back Home',
                'leading' => 'No notification for now',
                'image' => 'variation.png',
                'sub_leading' => 'Go now'
            ]
        )
    @else 
        
    @endif
        <div class="container" style="padding-top: 30px">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="page-header">
                        <h4><small class="pull-right">{{ $total }} Notification</small> Message </h4>
                    </div> 
                    <br/>
                    <div class="comments-list">
                        @foreach($notif_data as $nd)
                            <div class="media">
                                <p class="pull-right"><small>{{ $nd->created_at }}</small></p>
                                <a class="media-left" href="#">
                                    <img src="{{ url('img/others/bell.png') }}" width="40">
                                </a>
                                <div class="media-body">          
                                    <h4 class="media-heading user_name">Customer</h4>
                                    {{ $nd->message }}
                                    <p>
                                        <small><a href="{{ url('/delete-notifs') }}/{{ $nd->id }}">Delete</a></small>
                                        @if($nd->notif_active)
                                        | <small><a href="{{ url('/mark-notifs') }}/{{ $nd->id }}">Mark as Read</a></small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>      
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>

        <style>
            .user_name{
                font-size:14px;
                font-weight: bold;
            }
            .comments-list .media{
                border-bottom: 1px dotted #ccc;
            }
        </style>

@endsection