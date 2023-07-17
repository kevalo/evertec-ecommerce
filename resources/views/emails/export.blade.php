<style>
    div {
        background-color: white;
        padding: 14px;
        color: black;
        font-family: Arial, serif;
        border-radius: 4px;
        box-shadow: 0 0 4px 1px;
    }
</style>
<div>
    <h2>{{ env('APP_NAME') }}</h2>
    {{ $text }}<br>
    <a target="_blank" href="{{ $link }}">{{__('labels.download')}}</a>
</div>
