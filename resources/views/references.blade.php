@php

$refs = [
    [
        'img' => '/img/pilote_avion.png',
        'title' => "Pilote d'avion",
    ],
    [
        'img' => '/img/bg_NY.jpg',
        'title' => 'References',
    ],
    [
        'img' => '/img/bg_multi_NY.jpg',
        'title' => 'References',
    ],
    [
        'img' => '/img/bg_RIOPARIS.jpg',
        'title' => 'References',
    ],
    [
        'img' => '/img/bg_multi_img_RIOPARIS.jpg',
        'title' => 'References',
    ],
    [
        'img' => '/img/bg_MALAYSIE.jpg',
        'title' => 'References',
    ],
    [
        'img' => '/img/bg_multi_img_MALAYSIE.jpg',
        'title' => 'References',
    ],
];
@endphp

<table>
    <tr>
        <th>Image</th>
        <th>Lien</th>
    </tr>
    @foreach ($refs as $data)
        <tr>

            <td>
                <img src="{{ $data['img'] }}" width="100px">
            </td>
            <td>
                {{ $data['title'] }}
            </td>
        </tr>
    @endforeach

    <tr>
        <td>
            Données des crashs
        </td>
        <td>
            <a href="https://aviation-safety.net/">https://aviation-safety.net/</a>
        </td>
    </tr>

</table>
