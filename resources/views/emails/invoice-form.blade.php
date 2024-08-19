@component('mail::message')

    # {{ $data['invoice_subject'] }}



{{--@component('mail::button', ['url' => $data['url']])
Button Text
@endcomponent--}}

    {{ $data['invoice_message'] }}
@endcomponent
