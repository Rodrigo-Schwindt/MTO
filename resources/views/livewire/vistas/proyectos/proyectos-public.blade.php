<div
    x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 50)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 transform -translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
>
    <section class="project-banner">
        @if($banner && $banner->banner_image)
            <img src="{{ Storage::url($banner->banner_image) }}" alt="Proyectos" class="project-banner-image">
        @else
            <div class="project-banner-fallback"></div>
        @endif

        <div class="project-banner-overlay"></div>

        <div class="project-banner-inner">
            <nav class="project-breadcrumb">
                <a wire:navigate href="{{ url('/') }}">Inicio</a>
                <span>›</span>
                <span>Proyectos realizados</span>
            </nav>

            <h1>Nuestros proyectos</h1>
        </div>
    </section>

    <section class="project-list">
        <div class="project-grid">
            @forelse($projects as $project)
                @php $cover = $project->mainImage ?: $project->images->first(); @endphp
                <a wire:navigate href="{{ route('proyectos.detalle', $project->slug) }}" class="project-card">
                    @if($cover)
                        <img src="{{ Storage::url($cover->image) }}" alt="{{ $project->title }}" loading="lazy">
                    @endif
                    <span>{{ $project->title }}</span>
                </a>
            @empty
                <p class="project-empty">No hay proyectos disponibles.</p>
            @endforelse
        </div>
    </section>

    <style>
        .project-banner {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background: #143a1a;
        }

        .project-banner-image,
        .project-banner-fallback {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .project-banner-fallback {
            background: #143a1a;
        }

        .project-banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%), linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%), linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);
        }

        .project-banner-inner {
            position: relative;
            z-index: 2;
            max-width: 1224px;
            height: 100%;
            margin: 0 auto;
            padding-top: 94px;
            display: flex;
            flex-direction: column;
        }

        .project-breadcrumb {
            display: flex;
            gap: 5px;
            color: #fff;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 148px;
        }

        .project-breadcrumb a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .project-banner h1 {
            margin: 0;
            color: #fff;
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
            text-shadow: 0 6px 20px rgba(0,0,0,.55);
        }

        .project-list {
            padding: 72px 0 112px;
            background: #fff;
        }

        .project-grid {
            max-width: 1224px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
        }

        .project-card {
            position: relative;
            display: block;
            height: 280px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .project-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .project-card:hover img {
            transform: scale(1.05);
        }

        .project-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,.42) 100%);
            opacity: 0;
            transition: opacity .2s ease;
        }

        .project-card:hover::after {
            opacity: 1;
        }

        .project-card span {
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
            transition: opacity .2s ease;
            text-shadow: 0 4px 12px rgba(0,0,0,.5);
        }

        .project-card:hover span {
            opacity: 1;
        }

        .project-empty {
            grid-column: 1 / -1;
            text-align: center;
            color: #64748b;
            padding: 48px 0;
        }

        @media (max-width: 1199px) {
            .project-banner {
                height: 340px;
            }

            .project-banner-inner,
            .project-grid {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }

            .project-breadcrumb {
                margin-bottom: 85px;
            }

            .project-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .project-banner {
                height: 290px;
            }

            .project-banner-inner,
            .project-grid {
                margin-left: 20px;
                margin-right: 20px;
            }

            .project-banner-inner {
                padding-top: 74px;
            }

            .project-breadcrumb {
                margin-bottom: 76px;
            }

            .project-banner h1 {
                font-size: 32px;
            }

            .project-grid {
                grid-template-columns: 1fr;
            }

            .project-card {
                height: 260px;
            }
        }

        @keyframes proj-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .project-breadcrumb {
            animation: proj-fade-up 0.65s ease 0.38s both;
        }

        .project-banner h1 {
            animation: proj-fade-up 0.65s ease 0.52s both;
        }

        .project-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .project-card.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
    (function() {
        function initReveal() {
            var cards = Array.prototype.slice.call(document.querySelectorAll('.project-card'));
            if (!cards.length) return;

            cards.forEach(function(c) { c.classList.remove('is-visible'); });

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    var index = cards.indexOf(entry.target);
                    setTimeout(function() {
                        entry.target.classList.add('is-visible');
                    }, (index % 4) * 70);
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.05 });

            cards.forEach(function(card) { observer.observe(card); });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() { setTimeout(initReveal, 520); });
        } else {
            setTimeout(initReveal, 520);
        }

        document.addEventListener('livewire:navigated', initReveal);
    })();
    </script>
</div>
