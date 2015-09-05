{{-- Part of phoenix project. --}}

@extends('_global.{$package.name.lower$}.pure')

@section('toolbar')
    @include('toolbar')
@stop

@section('body')
<div id="phoenix-admin" class="{$controller.list.name.lower$}-container grid-container">
    <form name="admin-form" id="admin-form" action="{{ $uri['full'] }}" method="POST" enctype="multipart/form-data">

        <div class="filter-bar">
            {{ $filterBar->render(array('form' => $filterForm, 'show' => $showFilterBar)) }}
        </div>

        <div class="grid-table">
            <table class="table table-bordered">
                <thead>
                <tr>
                    {{-- TITLE --}}
                    <th>
                        {{ $grid->sortTitle('Title', '{$controller.item.name.lower$}.title') }}
                    </th>

                    {{-- AUTHOR --}}
                    <th>
                        {{ $grid->sortTitle('Author', '{$controller.item.name.lower$}.created_by') }}
                    </th>

                    {{-- CREATED --}}
                    <th>
                        {{ $grid->sortTitle('Created', '{$controller.item.name.lower$}.created') }}
                    </th>

                    {{-- LANGUAGE --}}
                    <th>
                        {{ $grid->sortTitle('Language', '{$controller.item.name.lower$}.language') }}
                    </th>

                    {{-- ID --}}
                    <th>
                        {{ $grid->sortTitle('ID', '{$controller.item.name.lower$}.id') }}
                    </th>
                </tr>
                </thead>

                <tbody>
                @foreach ($items as $i => $item)
                    <?php
                    $grid->setItem($item, $i);
                    ?>
                    <tr>
                        <td>
                            <a href="#" onclick="parent.{{ $function }}('{{ $selector }}', '{{ $item->id }}', '{{ $item->title }}');">
                                <span class="glyphicon glyphicon-menu-left text-muted"></span> {{{ $item->title }}}
                            </a>
                        </td>
                        <td>
                            {{{ $item->created_by }}}
                        </td>
                        <td>
                            {{{ $item->created }}}
                        </td>
                        <td>
                            {{{ $item->language }}}
                        </td>
                        <td>
                            {{{ $item->id }}}
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="25">
                        {{ $pagination->render($package->getName() . ':{$controller.list.name.lower$}', 'windwalker.pagination.phoenix') }}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="hidden-inputs">
            <input type="hidden" name="_method" value="PUT" />
            {{ \Windwalker\Core\Security\CsrfProtection::input() }}
        </div>

        @include('batch')
    </form>
</div>
@stop
