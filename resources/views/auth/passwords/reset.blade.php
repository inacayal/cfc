@extends('voyager::main.layout')
@section('content')
<div class="faded-bg animated"></div>
        <div class="hidden-xs col-sm-7 col-md-8" >
            <div class="clearfix">
                <?php $admin_logo_img = \Voyager::setting('admin.icon_image', ''); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-4 login-sidebar" >
            <div class="login-container" style="padding-top:0px;">
                <div class="copy animated fadeIn container-fluid" style="padding:0px; margin-top:-100px">
                    <div class="row">
                        <div class="col-xs-1" >
                            <img style="padding:10px 0px 0px 0px" class="img-responsive pull-left flip logo hidden-xs animated fadeIn" src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                        </div>
                        <div class="col-xs-11" style='margin:0px'>
                            <h1 style='color:#757c85;margin-top:0px'>{{ Voyager::setting('admin.title', 'Voyager') }}</h1>
                            <p style='color:#757c85'>{{ Voyager::setting('admin.description', __('voyager::login.welcome')) }}</p>
                        </div>
                    </div> 
                </div>
                <div class="card-body">
                    <p>Recupera tu contraseña</p>
                    <form method="POST" action="{{ route('generar.contrasena') }}">
                        @csrf

                        <input type="hidden" name="reset_token" value="{{ $token }}">
                        @if ($errors->has('reset_token'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{$errors->first('password')}}  </strong>
                            </span>
                        @endif

                        <div class="form-group form-group-default" id="emailGroup">
                            <label>correo electrónico</label>
                            <div class="controls">
                                <input readonly type="text" name="email" id="email" value="{{$email}}" placeholder="correo" class="form-control" required>
                            </div>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$errors->first('email')}}  </strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group form-group-default" id="passwordGroup">
                            <label>contraseña</label>
                            <div class="controls">
                                <input  type="password" name="password" class="form-control" required>
                            </div>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$errors->first('password')}}  </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group form-group-default" id="passwordGroup">
                            <label>confirmar contraseña</label>
                            <div class="controls">
                                <input  id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <button type="submit" class="btn btn-primary" style="width:100%;background-color:#004479;padding:5px;">
                                Reestablecer contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
