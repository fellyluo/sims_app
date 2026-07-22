<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    @include('kartu-guru._card-style')
    <style>
        @page { margin: 7mm 10mm; }
        body { margin: 0; }
        table.sheet { width: 100%; border-collapse: collapse; }
        table.sheet td.slot { width: 33.33%; padding: 1.2mm; vertical-align: top; }
    </style>
</head>
<body>
@foreach($pages as $page)
<div @if(! $loop->last) style="page-break-after: always;" @endif>
    <table class="sheet">
        @foreach($page->chunk(3) as $row)
        <tr>
            @foreach($row as $card)
            <td class="slot">@include('kartu-guru._card', ['card' => $card])</td>
            @endforeach
            @for($i = $row->count(); $i < 3; $i++)<td class="slot"></td>@endfor
        </tr>
        @endforeach
    </table>
</div>
@endforeach
</body>
</html>
