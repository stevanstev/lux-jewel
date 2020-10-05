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
                                @if($nd->notif_active)
                                    <p class="pull-right"><small>{{ $nd->created_at }}</small></p>
                                    <a class="media-left" href="#">
                                        <img src="{{ url('img/others/bell.png') }}"  width="40">
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
                                @else 
                                    <p style="color: gray;" class="pull-right"><small>{{ $nd->created_at }}</small></p>
                                    &nbsp;
                                    &nbsp;
                                    <a class="media-left" href="#" style="color: gray">
                                        <span class="fa fa-bell-o" style="color: gray"></span>
                                    </a>
                                    &nbsp;
                                    &nbsp;
                                    <div class="media-body" style="color: gray;">          
                                        <h4 class="media-heading user_name" style="color: gray;">Customer</h4>
                                        {{ $nd->message }}
                                        <p>
                                            <small><a href="{{ url('/delete-notifs') }}/{{ $nd->id }}">Delete</a></small>
                                            @if($nd->notif_active)
                                            | <small><a href="{{ url('/mark-notifs') }}/{{ $nd->id }}">Mark as Read</a></small>
                                            @endif
                                        </p>
                                    </div>
                                @endif
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

            #grayscaling {
                -webkit-filter: grayscale(100%);
                filter: grayscale(100%);
            }
        </style>

@endsection