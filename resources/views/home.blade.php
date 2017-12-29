@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

              @if(! auth()->user()->confirmed)  

                    <div class="panel-body">
                            
                        <p style="color: red"><strong>You need to confirm your email</strong></p>

                    </div>

              @else

                <div class="panel-body">
                    You are logged in!
                </div>

            @endif

            </div>
        </div>
    </div>
</div>
@endsection
