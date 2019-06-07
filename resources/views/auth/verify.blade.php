@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('auth.verify_title') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('auth.verify_link_success') }}
                        </div>
                    @endif

                    {{ __('auth.verify_link_notice') }}
                    {{ __('auth.verify_link_not_receive') }}, <a href="{{ route('verification.resend') }}">{{ __('auth.verify_link_resend') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
