<div>
<style>
/* === Ralux: animaciones de entrada === */
@keyframes ralux-fade-up {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0);    }
}
@keyframes ralux-fade-in {
    from { opacity: 0; }
    to   { opacity: 1; }
}

.ralux-anim-title {
    animation: ralux-fade-up 0.75s cubic-bezier(.25,.46,.45,.94) 0.15s both;
}
.ralux-anim-desc {
    animation: ralux-fade-up 0.75s cubic-bezier(.25,.46,.45,.94) 0.32s both;
}
.ralux-anim-btn-slide {
    animation: ralux-fade-up 0.75s cubic-bezier(.25,.46,.45,.94) 0.50s both;
}
.ralux-anim-bar {
    animation: ralux-fade-in 0.65s ease 0.25s both;
}

#banner {
    position: relative;
    width: 100%;
    height: 768px;
    overflow: hidden;
    background: #111;
}

.banner-slide {
    position: absolute;
    inset: 0;
    transition: opacity 0.7s ease;
}

.banner-media {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.banner-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(90deg, rgba(0, 0, 0, 0.72) 0%, rgba(0, 0, 0, 0.48) 42%, rgba(0, 0, 0, 0.16) 100%),
        linear-gradient(0deg, rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.25));
}

.banner-content-inner {
    position: relative;
    z-index: 2;
    height: 100%;
    max-width: 1224px;
    margin: 0 auto;
    padding: 78px 0px 82px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
}

.banner-title {
    max-width: 650px;
    color: #fff;
    font-size: 48px;
    font-weight: 700;
    line-height: 1.18;
    margin: 0;
    text-shadow: 0 2px 12px rgba(0, 0, 0, 0.34);
}

.banner-description {
    max-width: 610px;
    color: rgba(255, 255, 255, 0.92);
    font-size: 20px;
    font-weight: 400;
    line-height: 1.35;
    margin: 12px 0 0;
    text-shadow: 0 1px 10px rgba(0, 0, 0, 0.28);
}

.ralux-banner-btn {
    margin-top: 28px;
    min-width: 183px;
    height: 41px;
    padding: 0 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255, 255, 255, 0.82);
    color: #fff;
    font-size: 16px;
    font-weight: 400;
    line-height: 1;
    text-decoration: none;
    transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease;
}

.ralux-banner-btn:hover {
    background: #fff;
    border-color: #fff;
    color: #111;
}

#banner-dots {
    position: absolute;
    left: max(calc((100% - 1224px) / 2), 28px);
    bottom: 122px;
    z-index: 5;
    display: flex;
    gap: 11px;
}

#banner-dots button {
    width: 43.912px;
    height: 6px;
    padding: 0;
    border: 0;
    border-radius: 0;
    background: rgba(255, 255, 255, 0.52);
    cursor: pointer;
    transition: background-color 0.25s ease, opacity 0.25s ease;
}

#banner-dots button.is-active {
    background: #fff;
}

.home-representatives-wrap {
    position: relative;
    z-index: 10;
    height: 135px;
    width: 1224px;
    margin: -67.5px auto 67.5px;
}

.home-representatives-card {
    min-height: 135px;
    padding: 26px 56px;
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.12);

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 56px;
}

.home-representatives-title {
    color: #111;
    font-size: 20px;
    font-weight: 600;
    line-height: 1.2;
    white-space: nowrap;
}

.home-representatives-logos {
    display: flex;
    align-items: center;
    gap: 28px;
}

.home-representatives-logo {
    display: block;
    max-width: 267px;
    max-height: 74px;
    object-fit: contain;
}

.home-representatives-logos--3 .home-representatives-logo {
    max-width: 200px;
}

.home-representatives-carousel {
    flex: 1;
    overflow: hidden;
    min-width: 0;
}

.home-representatives-track {
    display: flex;
    align-items: center;
    gap: 28px;
    will-change: transform;
}

.home-representatives-slide {
    flex: 0 0 calc((100% - 56px) / 3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.home-services {
    width: 1224px;
    margin: 0 auto 78px;
}

.home-services-title {
    color: #404041;
    font-size: 32px;
    font-weight: 700;
    line-height: 1.2;
    margin: 0 0 22px;
}

.home-services-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.home-service-card {
    position: relative;
    display: block;
    height: 360px;
    overflow: hidden;
    background: #e5e7eb;
}

.home-service-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .45s ease;
}

.home-service-card:hover img {
    transform: scale(1.05);
}

.home-service-card::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0) 36%, rgba(0, 0, 0, .48) 100%);
}

.home-service-card-title {
    position: absolute;
    left: 24px;
    right: 24px;
    bottom: 34px;
    z-index: 2;
    color: #fff;
    font-size: 28px;
    font-weight: 700;
    line-height: 1.15;
    text-align: center;
    text-shadow: 0 4px 14px rgba(0, 0, 0, .45);
}

.home-projects {
    max-width: 1224px;
    margin: 50px auto 50px;
}

.home-projects-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 27px;
}

.home-projects-title {
    margin: 0;
    color: #404041;
    font-size: 32px;
    font-weight: 700;
    line-height: 1.2;
}

.home-projects-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.home-projects-view-all {
    min-width: 114px;
    height: 41px;
    padding: 0 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #737275;
    color: #737275;
    font-size: 16px;
    font-weight: 600;
    line-height: 1;
    text-decoration: none;
    transition: border-color .2s ease, color .2s ease, background-color .2s ease;
}

.home-projects-view-all:hover {
    border-color: #52A028;
    color: #52A028;
}

.home-projects-track {
    display: flex;
    gap: 24px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scroll-snap-type: x mandatory;
    scrollbar-width: none;
}

.home-projects-track::-webkit-scrollbar {
    display: none;
}

.home-projects-dots {
    display: none;
    justify-content: center;
    gap: 10px;
    margin-top: 28px;
}

[data-home-carousel].has-carousel .home-projects-dots {
    display: flex;
}

.home-projects-dots button {
    width: 43.912px;
    height: 6px;
    padding: 0;
    border: 0;
    border-radius: 0;
    background: #eeeeee;
    cursor: pointer;
    transition: background-color .2s ease;
}

.home-projects-dots button.is-active {
    background: #c7c7c7;
}

.home-project-card {
    position: relative;
    display: block;
    flex: 0 0 calc((100% - 72px) / 4);
    height: 276px;
    overflow: hidden;
    background: #e5e7eb;
    scroll-snap-align: start;
}

.home-project-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .45s ease;
}

.home-project-card:hover img {
    transform: scale(1.05);
}

.home-project-card::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0) 38%, rgba(0,0,0,.42) 100%);
    opacity: 0;
    transition: opacity .2s ease;
}

.home-project-card:hover::after {
    opacity: 1;
}

.home-project-card-title {
    position: absolute;
    left: 18px;
    right: 18px;
    bottom: 18px;
    z-index: 2;
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.15;
    opacity: 0;
    text-shadow: 0 4px 12px rgba(0,0,0,.48);
    transition: opacity .2s ease;
}

.home-project-card:hover .home-project-card-title {
    opacity: 1;
}

.home-clients {
    max-width: 1224px;
    margin: 76px auto 78px;
}

.home-clients-track {
    display: flex;
    gap: 24px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scroll-snap-type: none;
    scrollbar-width: none;
}

.home-clients-track::-webkit-scrollbar {
    display: none;
}

.home-client-card {
    flex: 0 0 calc((100% - 120px) / 6);
    height: 104px;
    box-sizing: border-box;
    border: 0;
    box-shadow: inset 0 0 0 1px #e5e7eb;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}

.home-client-card img {
    max-width: 100%;
    max-height: 104px;
    object-fit: contain;
    filter: grayscale(100%);
    opacity: .82;
    transition: filter .3s ease, opacity .3s ease;
}

.home-client-card:hover img {
    filter: grayscale(0%);
    opacity: 1;
}

.home-memberships {
    max-width: 1224px;
    margin: 0 auto 78px;
}

.home-memberships-title {
    margin: 0 0 28px;
    color: #303236;
    font-size: 32px;
    font-weight: 700;
    line-height: 1.2;
}

.home-memberships-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 32px 70px;
    align-items: center;
}

.home-membership-logo {
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.home-membership-logo img {
    max-width: 100%;
    max-height: 58px;
    object-fit: contain;
}

/* scroll reveal */
.ralux-reveal {
    opacity: 0;
    transform: translateY(26px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}
.ralux-reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}
.ralux-reveal-delay {
    transition-delay: 0.15s;
}

/* === Responsive: sólo aplica por debajo de 1200px === */
@media (max-width: 1199px) {
    #banner {
        height: 520px !important;
    }
    .banner-content-inner {
        max-width: 100% !important;
        padding-left: 28px !important;
        padding-right: 28px !important;
    }
    .banner-title {
        font-size: 30px !important;
    }
    .banner-description {
        font-size: 17px !important;
        max-width: 90% !important;
    }
    .ralux-banner-btn {
        margin-top: 24px !important;
    }
    #banner-dots {
        left: 28px !important;
        bottom: 44px !important;
    }
    .home-representatives-wrap {
        width: auto !important;
        height: auto !important;
        margin-top: -46px !important;
        margin-left: 28px !important;
        margin-right: 28px !important;
        margin-bottom: 44px !important;
    }
    .home-representatives-card {
        min-height: 92px !important;
        padding: 22px 32px !important;
        gap: 32px !important;
    }
    .home-representatives-logo {
        max-width: 160px !important;
    }
    .home-representatives-slide {
        flex: 0 0 calc((100% - 56px) / 3) !important;
    }
    .home-services {
        width: auto !important;
        margin: 0 28px 58px !important;
    }
    .home-service-card {
        height: 310px !important;
    }
    .home-services-title {
        font-size: 24px !important;
    }
    .home-projects {
        max-width: none !important;
        margin: 58px 28px 60px !important;
    }
    .home-projects-title {
        font-size: 24px !important;
    }
    .home-projects-header {
        margin-bottom: 26px !important;
    }
    .home-project-card {
        flex-basis: calc((100% - 48px) / 3) !important;
        height: 250px !important;
    }
    .home-clients {
        max-width: none !important;
        margin: 58px 28px 60px !important;
    }
    .home-client-card {
        flex-basis: calc((100% - 72px) / 4) !important;
    }
    .home-memberships {
        max-width: none !important;
        margin: 0 28px 60px !important;
    }
    .home-memberships-title {
        font-size: 24px !important;
    }
    .home-memberships-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        gap: 28px !important;
    }
    .ralux-searchbar {
        height: auto !important;
        padding-top: 22px !important;
        padding-bottom: 22px !important;
    }
    .ralux-desc-input {
        width: 100% !important;
    }
}

@media (max-width: 767px) {
    #banner {
        height: 430px !important;
    }
    .banner-content-inner {
        padding-left: 20px !important;
        padding-right: 20px !important;
        padding-top: 56px !important;
        padding-bottom: 74px !important;
    }
    .banner-title {
        font-size: 26px !important;
    }
    .banner-description {
        font-size: 14px !important;
        max-width: 100% !important;
    }
    .ralux-banner-btn {
        margin-top: 22px !important;
    }
    #banner-dots {
        left: 20px !important;
        bottom: 36px !important;
    }
    .home-representatives-wrap {
        padding: 0 !important;
        margin-top: -58px !important;
        margin-left: 20px !important;
        margin-right: 20px !important;
        margin-bottom: 34px !important;
    }
    .home-representatives-card {
        min-height: 116px !important;
        padding: 20px !important;
        flex-direction: column !important;
        gap: 16px !important;
    }
    .home-representatives-title {
        font-size: 16px !important;
        white-space: normal !important;
        text-align: center !important;
    }
    .home-representatives-logos {
        gap: 18px !important;
        justify-content: center !important;
        flex-wrap: wrap !important;
    }
    .home-representatives-logo {
        max-width: 132px !important;
        max-height: 48px !important;
    }
    .home-representatives-carousel {
        width: 100% !important;
        flex: none !important;
    }
    .home-representatives-track {
        gap: 14px !important;
    }
    .home-representatives-slide {
        flex: 0 0 calc((100% - 14px) / 2) !important;
    }
    .home-services {
        margin: 0 20px 48px !important;
    }
    .home-services-grid {
        grid-template-columns: 1fr !important;
        gap: 18px !important;
    }
    .home-service-card {
        height: 250px !important;
    }
    .home-service-card-title {
        font-size: 22px !important;
        bottom: 24px !important;
    }
    .home-projects {
        margin: 48px 20px 50px !important;
    }
    .home-projects-header {
        align-items: flex-start !important;
        margin-bottom: 22px !important;
    }
    .home-projects-title {
        max-width: 190px !important;
        font-size: 22px !important;
    }
    .home-projects-actions {
        gap: 8px !important;
    }
    .home-projects-view-all {
        min-width: 86px !important;
        padding: 0 14px !important;
    }
    .home-project-card {
        flex-basis: 100% !important;
        height: 244px !important;
    }
    .home-clients {
        margin: 48px 20px 50px !important;
    }
    .home-client-card {
        flex-basis: calc((100% - 24px) / 2) !important;
        height: 86px !important;
        padding: 18px !important;
    }
    .home-memberships {
        margin: 0 20px 50px !important;
    }
    .home-memberships-title {
        font-size: 22px !important;
        margin-bottom: 20px !important;
    }
    .home-memberships-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 22px 28px !important;
    }
    .home-membership-logo {
        height: 64px !important;
    }
    .ralux-searchbar {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }
    .home-client-card img {
        filter: grayscale(0%) !important;
        opacity: 1 !important;
    }
}
</style>
@if($sliders->count() > 0)
<div id="banner">

    @foreach($sliders as $index => $slider)
    @php
        $ext = strtolower(pathinfo($slider->image, PATHINFO_EXTENSION));
        $isVideo = in_array($ext, ['mp4','webm','ogg','mov','avi']);
        $buttonText = trim((string) ($slider->button_text ?? ''));
        $buttonUrl = trim((string) ($slider->url ?? '')) ?: route('contacto');
        $buttonTarget = $slider->button_target === '_blank' ? '_blank' : '_self';
        $showButton = $buttonText !== '';
    @endphp

    <div class="banner-slide" style="opacity:{{ $index === 0 ? '1' : '0' }};">

        @if($isVideo)
            <video class="banner-media" autoplay loop muted playsinline>
                <source src="{{ Storage::url($slider->image) }}" type="video/{{ $ext }}">
            </video>
        @else
            <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}"
                 class="banner-media">
        @endif

        <div class="banner-overlay"></div>

        <div class="banner-content-inner">
            <h1 class="banner-title ralux-anim-title">
                {{ $slider->title }}
            </h1>
            @if($slider->description)
            @php
                $sliderDescription = html_entity_decode(
                    preg_replace('/(&nbsp;)+$/', '', strip_tags($slider->description)),
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                );
            @endphp
            <p class="banner-description ralux-anim-desc">
                {{ $sliderDescription }}
            </p>
            @endif
            @if($showButton)
            <a href="{{ $buttonUrl }}"
               target="{{ $buttonTarget }}"
               @if($buttonTarget === '_blank') rel="noopener noreferrer" @endif
               class="ralux-banner-btn ralux-anim-btn-slide">
                {{ $buttonText }}
            </a>
            @endif
        </div>
    </div>
    @endforeach

    @if($sliders->count() > 1)
    <div id="banner-dots">
        @foreach($sliders as $i => $s)
        <button onclick="bannerGo({{ $i }})"
                id="dot-{{ $i }}"
                class="{{ $i === 0 ? 'is-active' : '' }}"
                aria-label="Ir al slide {{ $i + 1 }}">
        </button>
        @endforeach
    </div>
    @endif
</div>

<script>
(function() {
    var slides = document.querySelectorAll('#banner .banner-slide');
    var dots   = document.querySelectorAll('#banner-dots button');
    var total  = slides.length;
    var current = 0;
    var timer;

    function go(n) {
        slides[current].style.opacity = '0';
        if (dots[current]) { dots[current].classList.remove('is-active'); }
        current = (n + total) % total;
        slides[current].style.opacity = '1';
        if (dots[current]) { dots[current].classList.add('is-active'); }
    }

    window.bannerGo = function(n) { clearInterval(timer); go(n); startTimer(); };

    function startTimer() {
        if (total > 1) timer = setInterval(function() { go(current + 1); }, 5000);
    }

    startTimer();
})();
</script>
@endif

@php
    $repLogos     = collect($representatives?->logos ?? [])->filter()->values();
    $repLogoCount = $repLogos->count();
@endphp
@if($representatives && ($representatives->title || $repLogoCount > 0))
<section class="home-representatives-wrap" aria-label="Representantes">
    <div class="home-representatives-card">
        @if($representatives->title)
            <p class="home-representatives-title">{{ $representatives->title }}</p>
        @endif

        @if($repLogoCount <= 3)
            <div class="home-representatives-logos home-representatives-logos--{{ $repLogoCount }}">
                @foreach($repLogos as $i => $logo)
                    <img src="{{ Storage::url($logo) }}" alt="Representante {{ $i + 1 }}" class="home-representatives-logo">
                @endforeach
            </div>
        @else
            <div class="home-representatives-carousel" id="rep-carousel">
                <div class="home-representatives-track" id="rep-track">
                    @foreach($repLogos as $i => $logo)
                        <div class="home-representatives-slide">
                            <img src="{{ Storage::url($logo) }}" alt="Representante {{ $i + 1 }}" class="home-representatives-logo">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@if($repLogoCount > 3)
<script>
(function() {
    var track = document.getElementById('rep-track');
    if (!track) return;

    var origSlides = Array.from(track.querySelectorAll('.home-representatives-slide'));
    var n = origSlides.length;

    origSlides.forEach(function(s) { track.appendChild(s.cloneNode(true)); });

    var current = 0;
    var step = 0;

    function computeStep() {
        var r0 = origSlides[0].getBoundingClientRect();
        var r1 = origSlides[1].getBoundingClientRect();
        step = r1.left - r0.left;
    }

    function slideNext() {
        current++;
        track.style.transition = 'transform 0.6s ease';
        track.style.transform = 'translateX(-' + (current * step) + 'px)';

        if (current >= n) {
            setTimeout(function() {
                track.style.transition = 'none';
                current = 0;
                track.style.transform = 'translateX(0)';
            }, 650);
        }
    }

    setTimeout(function() { computeStep(); if (step > 0) setInterval(slideNext, 3500); }, 0);
})();
</script>
@endif
@endif

@if($featuredServices->isNotEmpty())
<section class="home-services ralux-reveal" aria-label="Servicios destacados">
    <h2 class="home-services-title">&iquest;Que hacemos?</h2>

    <div class="home-services-grid">
        @foreach($featuredServices as $service)
            <a wire:navigate
               href="{{ route('servicios.detalle', $service->slug) }}"
               class="home-service-card">
                @if($service->image)
                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->title }}" loading="lazy">
                @endif
                <span class="home-service-card-title">{{ $service->title }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

@if(false)
<div class="ralux-anim-bar ralux-searchbar bg-[#222] h-[134px] flex items-center relative z-10"
    x-data="{
        tipoId: '', marcaId: '', modeloId: '', busqueda: '',
        tipoOpen: false, tipoSearch: '',
        marcaOpen: false, marcaSearch: '',
        modeloOpen: false, modeloSearch: '',
        tipoOpts:  window._raluxHomeData.tipos,
        marcaOpts: window._raluxHomeData.marcas,
        get modeloOpts() {
            const all = window._raluxHomeData.modelos;
            return this.marcaId ? all.filter(m => (m.marca_ids || [m.marca_id]).includes(this.marcaId)) : all;
        },
        tipoLabel()   { const f = this.tipoOpts.find(o => o.value === this.tipoId);   return f ? f.label : 'Seleccione categoría'; },
        marcaLabel()  { const f = this.marcaOpts.find(o => o.value === this.marcaId); return f ? f.label : 'Seleccione marca'; },
        modeloLabel() { const f = window._raluxHomeData.modelos.find(o => o.value === this.modeloId); return f ? f.label : 'Seleccione modelo'; },
        selectTipo(v)   { this.tipoId = v; this.tipoOpen = false; this.tipoSearch = ''; },
        selectMarca(v)  {
            this.marcaId = v; this.marcaOpen = false; this.marcaSearch = '';
            if (this.modeloId && v) {
                const m = window._raluxHomeData.modelos.find(m => m.value === this.modeloId);
                if (m && !(m.marca_ids || [m.marca_id]).includes(v)) this.modeloId = '';
            }
            if (!v) this.modeloId = '';
        },
        selectModelo(v) {
            this.modeloId = v; this.modeloOpen = false; this.modeloSearch = '';
            if (v) { const m = window._raluxHomeData.modelos.find(m => m.value === v); if (m) this.marcaId = m.marca_id; }
        },
        limpiar() { this.tipoId = ''; this.marcaId = ''; this.modeloId = ''; this.busqueda = ''; },
        irAProductos() {
            const p = new URLSearchParams();
            if (this.tipoId)          p.set('tipo_id',   this.tipoId);
            if (this.marcaId)         p.set('marca_id',  this.marcaId);
            if (this.modeloId)        p.set('modelo_id', this.modeloId);
            if (this.busqueda.trim()) p.set('busqueda',  this.busqueda.trim());
            const url = '/productos' + (p.toString() ? '?' + p.toString() : '');
            if (typeof Livewire !== 'undefined' && Livewire.navigate) { Livewire.navigate(url); } else { window.location.href = url; }
        }
    }"
    @click.outside="tipoOpen = false; marcaOpen = false; modeloOpen = false">

    
</div>

@endif
<div class="ralux-reveal">
    <livewire:vistas.home.nosotros-home />
</div>

@if($featuredProjects->isNotEmpty())
<section class="home-projects ralux-reveal {{ $featuredProjects->count() > 4 ? 'has-carousel' : '' }}"
         data-home-carousel
         data-carousel-card=".home-project-card"
         aria-label="Proyectos destacados">
    <div class="home-projects-header">
        <h2 class="home-projects-title">Conoc&eacute; nuestros proyectos</h2>

        <div class="home-projects-actions">
            <a wire:navigate href="{{ route('proyectos.public') }}" class="home-projects-view-all">Ver todos</a>
        </div>
    </div>

    <div class="home-projects-track" data-carousel-track>
        @foreach($featuredProjects as $project)
            @php $cover = $project->mainImage ?: $project->images->first(); @endphp
            <a wire:navigate
               href="{{ route('proyectos.detalle', $project->slug) }}"
               class="home-project-card">
                @if($cover)
                    <img src="{{ Storage::url($cover->image) }}" alt="{{ $project->title }}" loading="lazy">
                @endif
                <span class="home-project-card-title">{{ $project->title }}</span>
            </a>
        @endforeach
    </div>

    <div class="home-projects-dots" data-carousel-dots aria-label="Navegaci&oacute;n de proyectos"></div>
</section>
@endif

<div class="bg-[#F5F5F5]">
    <div class="max-w-[1224px] mx-auto">
        <div class="ralux-reveal ralux-reveal-delay">
            <livewire:vistas.novedades.destacadas/>
        </div>
    </div>
</div>

@if($featuredBrands->isNotEmpty())
<section class="home-clients ralux-reveal {{ $featuredBrands->count() > 6 ? 'has-carousel' : '' }}"
         data-home-carousel
         data-carousel-card=".home-client-card"
         aria-label="Clientes destacados">
    <div class="home-projects-header">
        <h2 class="home-projects-title">Clientes</h2>

        <div class="home-projects-actions">
            <a wire:navigate href="{{ route('clientes.public') }}" class="home-projects-view-all">Ver todos</a>
        </div>
    </div>

    <div class="home-clients-track" data-carousel-track>
        @foreach($featuredBrands as $brand)
            <a wire:navigate href="{{ route('clientes.public') }}" class="home-client-card">
                <img src="{{ Storage::url($brand->image) }}" alt="Cliente destacado" loading="lazy">
            </a>
        @endforeach
    </div>

    <div class="home-projects-dots" data-carousel-dots aria-label="Navegaci&oacute;n de clientes"></div>
</section>
@endif

@if($homeMemberships->isNotEmpty())
<section class="home-memberships ralux-reveal" aria-label="Pertenecemos a">
    <h2 class="home-memberships-title">Pertenecemos a</h2>

    <div class="home-memberships-grid">
        @foreach($homeMemberships as $membership)
            <div class="home-membership-logo">
                <img src="{{ Storage::url($membership->image) }}" alt="Pertenecemos a" loading="lazy">
            </div>
        @endforeach
    </div>
</section>
@endif

<script>
(function() {
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });

    document.querySelectorAll('.ralux-reveal').forEach(function(el) {
        observer.observe(el);
    });

    document.querySelectorAll('[data-home-carousel]').forEach(function(section) {
        var track = section.querySelector('[data-carousel-track]');
        var dotsWrap = section.querySelector('[data-carousel-dots]');
        var cardSelector = section.dataset.carouselCard || '.home-project-card';
        var carouselOffset = parseFloat(section.dataset.carouselOffset || '0') || 0;
        var autoplayTimer = null;
        var currentPage = 0;
        var activeLockUntil = 0;

        if (!track || !dotsWrap) return;

        function getGap() {
            return parseFloat(window.getComputedStyle(track).columnGap || window.getComputedStyle(track).gap) || 0;
        }

        function getVisibleCount(cards) {
            if (!cards.length) return 1;

            var cardWidth = cards[0].getBoundingClientRect().width;
            return Math.max(1, Math.floor((track.clientWidth + getGap()) / (cardWidth + getGap())));
        }

        function getCarouselData() {
            var cards = Array.prototype.slice.call(track.querySelectorAll(cardSelector));
            var dots = Array.prototype.slice.call(dotsWrap.querySelectorAll('button'));
            var visibleCount = getVisibleCount(cards);
            var pages = Math.ceil(cards.length / visibleCount);

            return { cards: cards, dots: dots, visibleCount: visibleCount, pages: pages };
        }

        function getCardLeft(card) {
            if (!card) return 0;

            var cardRect = card.getBoundingClientRect();
            var trackRect = track.getBoundingClientRect();

            return Math.max(0, Math.round(cardRect.left - trackRect.left + track.scrollLeft - carouselOffset));
        }

        function setActiveDot(page) {
            var dots = Array.prototype.slice.call(dotsWrap.querySelectorAll('button'));

            dots.forEach(function(dot, index) {
                dot.classList.toggle('is-active', index === page);
            });
        }

        function goToPage(page) {
            var data = getCarouselData();

            if (!data.pages || data.pages <= 1) return;

            currentPage = (page + data.pages) % data.pages;
            activeLockUntil = Date.now() + 650;
            setActiveDot(currentPage);

            var targetCard = data.cards[Math.min(currentPage * data.visibleCount, data.cards.length - 1)];

            if (targetCard) {
                track.scrollTo({ left: getCardLeft(targetCard), behavior: 'smooth' });
            }
        }

        function stopAutoplay() {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        }

        function startAutoplay() {
            stopAutoplay();

            if (getCarouselData().pages <= 1) return;

            autoplayTimer = setInterval(function() {
                goToPage(currentPage + 1);
            }, 5000);
        }

        function buildDots() {
            var cards = Array.prototype.slice.call(track.querySelectorAll(cardSelector));
            var visibleCount = getVisibleCount(cards);
            var pages = Math.ceil(cards.length / visibleCount);

            stopAutoplay();
            dotsWrap.innerHTML = '';
            section.classList.toggle('has-carousel', pages > 1);

            if (pages <= 1) return;

            for (var i = 0; i < pages; i++) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.setAttribute('aria-label', 'Ir al grupo de proyectos ' + (i + 1));
                dot.dataset.page = i;
                dot.addEventListener('click', function() {
                    stopAutoplay();
                    goToPage(parseInt(this.dataset.page, 10));
                    startAutoplay();
                });
                dotsWrap.appendChild(dot);
            }

            updateDots();
            startAutoplay();
        }

        function updateDots() {
            if (Date.now() < activeLockUntil) return;

            var cards = Array.prototype.slice.call(track.querySelectorAll(cardSelector));
            var dots = Array.prototype.slice.call(dotsWrap.querySelectorAll('button'));
            var visibleCount = getVisibleCount(cards);

            if (!dots.length) return;

            var activePage = 0;
            var smallestDistance = Infinity;

            dots.forEach(function(dot, index) {
                var targetCard = cards[Math.min(index * visibleCount, cards.length - 1)];
                var targetLeft = targetCard ? getCardLeft(targetCard) : Infinity;
                var distance = Math.abs(track.scrollLeft - targetLeft);

                if (distance < smallestDistance) {
                    smallestDistance = distance;
                    activePage = index;
                }
            });

            currentPage = activePage;
            setActiveDot(activePage);
        }

        buildDots();
        track.addEventListener('scroll', updateDots, { passive: true });
        track.addEventListener('mouseenter', stopAutoplay);
        track.addEventListener('mouseleave', startAutoplay);
        window.addEventListener('resize', buildDots);
    });
})();
</script>

</div>
