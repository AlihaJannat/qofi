@extends('layout.frontapp')

@section('title')
    @if ($page->page == 'term-n-condition')
        Terms & Condition
    @else
        {{ replaceHyphenAndCapitalize($page->page) }}
    @endif
@endsection

@section('content')
    <div class="p-8">
        <h1 class="text-4xl font-extrabold text-black-5 mt-8 mb-4">
            @if ($page->page == 'term-n-condition')
                Terms & Condition
            @else
                {{ replaceHyphenAndCapitalize($page->page) }}
            @endif
        </h1>

        <div class="mt-4 mb-8">
            {!! $page->content !!}
        </div>
    </div>
@endsection
