
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        @if(count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<div class="row message_error" style="display:none">
    <div class="col-12 col-md-6">
        <div class="alert alert-danger">
            <p class="text_error"> </p>
        </div>
    </div>
</div>
