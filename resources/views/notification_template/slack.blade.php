Exception: {{ $data->context['exception'] ?: __('common.unknown') }}
File: {{ $data->context['file'] ?: __('common.unknown') }}
Line: {{ $data->context['line'] ?: __('common.unknown') }}
User id: {{ isset($data->context['user_id']) ? $data->context['user_id'] : __('common.unknown') }}
Route url: {{ $data->context['route_url'] ?: __('common.unknown') }}
Git commit: {{ $data->context['commit'] ?: __('common.unknown') }}
Server Ip: {{ $data->context['server_ip'] ?: __('common.unknown') }}
Client Ip: {{ $data->context['client_ip'] ?: __('common.unknown') }}
