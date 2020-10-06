@extends('template/container', ['show' => false])

@section('title')
    Predict Not Found
@endsection

@section('section')
    @include('template/empty_page', 
        [
            'target' => 'prediction', 
            'button_text' => 'Back',
            'leading' => 'Prediction not found',
            'image' => 'predict.png',
            'sub_leading' => ''
        ]
    )
@endsection