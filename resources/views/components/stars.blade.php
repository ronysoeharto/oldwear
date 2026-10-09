@props(['rating' => 5])
<span class="stars" role="img" aria-label="Rating {{ (int) $rating }} dari 5">
    @for ($i = 1; $i <= 5; $i++)
        <x-icon name="star" @class(['off' => $i > (int) $rating]) />
    @endfor
</span>
