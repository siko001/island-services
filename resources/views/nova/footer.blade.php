<!-- Styles / Scripts -->
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
<div class="flex max-sm:flex-col-reverse justify-between items-baseline">
    <p class="text-3xl font-bold ">{{$company}}</p>
    <img class="mr-4 dark:text-white text-black"  style="width:102px;" src="{{ asset('images/isl-logo.svg') }}" alt="">
</div>
<p class="text-xs">Version: {{ $version }}</p>
