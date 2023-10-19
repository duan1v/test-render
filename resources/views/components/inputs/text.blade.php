<div>
    <input type="text" {{ $attributes }}> <br>
    {{$attributes->wire('model')->value()}} <br>
    {{$attributes->wire('model')->modifiers()}} <br>
    {{$attributes->wire('model')->hasModifier('defer')}} <br>
    {{$attributes->wire('loading')->hasModifier('class')}} <br>
    {{$attributes->wire('loading')->value()}} <br>
</div>
