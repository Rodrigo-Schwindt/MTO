<div
    x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 50)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 transform -translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
>
    <section class="relative w-full h-[450px] max-[1199px]:h-[340px] max-[767px]:h-[290px] max-[639px]:h-[250px] overflow-hidden">
        @if($banner && $banner->image_banner)
            <img src="{{ Storage::url($banner->image_banner) }}"
                 alt="Servicios"
                 class="absolute inset-0 w-full h-full object-cover">
        @else
            <div class="absolute inset-0 bg-slate-700"></div>
        @endif

        <div
            class="absolute inset-0"
            style="background: linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%), linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%), linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);"
        ></div>

        <div class="relative z-10 max-w-[1224px] mx-auto h-full px-4 lg:px-0 flex flex-col pt-[94px] max-[767px]:pt-[74px] max-[639px]:pt-[64px]">
            <nav class="svc-breadcrumb text-white text-[12px] leading-[150%] flex items-center gap-1 mb-[148px] max-[1199px]:mb-[85px] max-[767px]:mb-[76px] max-[639px]:mb-[60px] max-[1199px]:flex-wrap">
                <a wire:navigate href="{{ url('/') }}" class="font-bold drop-shadow">Inicio</a>
                <span class="drop-shadow">›</span>
                <span class="drop-shadow">Servicios</span>
            </nav>

            <h1 class="svc-h1 text-white text-[40px] font-bold leading-none uppercase max-[767px]:text-[32px] drop-shadow-[0_6px_20px_rgba(0,0,0,0.55)]">
                Servicios
            </h1>
        </div>
    </section>

    <section class="bg-white pt-[80px] pb-[96px] max-[1199px]:px-4 max-[767px]:pt-12 max-[767px]:pb-16">
        <div class="max-w-[1224px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-[24px] max-[767px]:gap-5">
                @forelse($services as $service)
                    <a wire:navigate
                       href="{{ route('servicios.detalle', $service->slug) }}"
                       class="svc-card group relative block h-[390px] max-[1199px]:h-[330px] max-[767px]:h-[260px] overflow-hidden bg-slate-100">
                        @if($service->image)
                            <img src="{{ Storage::url($service->image) }}"
                                 alt="{{ $service->title }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-10 max-[767px]:p-5 text-center">
                            <h2 class="text-white text-[25px] max-[767px]:text-[21px] font-bold leading-tight drop-shadow">
                                {{ $service->title }}
                            </h2>
                         
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-2 text-center py-16 text-slate-500">
                        No hay servicios disponibles.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @keyframes svc-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .svc-breadcrumb {
            animation: svc-fade-up 0.6s ease 0.38s both;
        }

        .svc-h1 {
            animation: svc-fade-up 0.6s ease 0.52s both;
        }

        .svc-card {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .svc-card.is-visible {
            opacity: 1;
        }
    </style>

    <script>
    (function() {
        function initReveal() {
            var cards = Array.prototype.slice.call(document.querySelectorAll('.svc-card'));
            if (!cards.length) return;

            cards.forEach(function(c) { c.classList.remove('is-visible'); });

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    var index = cards.indexOf(entry.target);
                    setTimeout(function() {
                        entry.target.classList.add('is-visible');
                    }, (index % 2) * 100);
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
