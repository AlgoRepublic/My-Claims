@extends('app')
@section('title', 'What We Do')
@section('mainbody')

    <div class="container">
        <div class="custom_whatWeDo_section">
            <div class="custom_page_heading">
                <h4>What We Do</h4>
            </div>
            {!! $settings['what_we_do'] or '' !!}
        </div>
    </div>
@endsection

