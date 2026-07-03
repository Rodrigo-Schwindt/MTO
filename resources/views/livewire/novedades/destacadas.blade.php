@section('page-title', 'Novedades / Destacadas')

@php
    use Illuminate\Support\Str;
@endphp

@if($destacadas->isNotEmpty())
<section class="novedades-dest-section bg-[#F5F5F5] w-full pt-[94px] pb-[72px] max-[1199px]:pt-[60px] max-[1199px]:pb-[80px] max-[767px]:pt-[40px] max-[767px]:pb-[48px]"
    x-data="{ show: false }"
    x-intersect.once.threshold.0.1="show = true">

    <div class="max-w-[1224px] mx-auto max-[1199px]:px-6 max-[767px]:px-4">
        <div class="flex items-center justify-between mb-[42px] transition-all duration-700 ease-out max-[767px]:mb-[28px]"
             :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">

            <h2 class="novedades-dest-title text-[#404041] text-[32px] font-bold leading-[120%]">
                Enterate de nuestras últimas novedades
            </h2>

            <a wire:navigate href="{{ route('novedades.public') }}"
               class="home-projects-view-all">
                Ver todos
            </a>
        </div>

        <div class="transition-all duration-700 delay-200 ease-out"
             :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
            <div class="swiper novedadesDestacadasSwiper">
                <div class="swiper-wrapper">
                    @foreach($destacadas as $nov)
                        @php
                            $category = $nov->novcategories->first()->title ?? 'Novedad';
                            $description = trim(html_entity_decode(strip_tags($nov->description), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                        @endphp

                        <div class="swiper-slide">
                            <a wire:navigate href="{{ route('novedad.detalle', $nov->id) }}" class="news-card">
                                <span class="news-card-image">
                                    @if($nov->image)
                                        <img src="{{ Storage::url($nov->image) }}"
                                             alt="{{ $nov->title }}"
                                             loading="lazy">
                                    @endif
                                </span>

                                <span class="news-card-body bg-white">
                                    <span class="news-card-category">{{ $category }}</span>
                                    <span class="news-card-title">{{ $nov->title }}</span>
                                    <span class="news-card-description">{{ Str::limit($description, 116) }}</span>
                                    <span class="news-card-link pb-[30px]">Leer m&aacute;s</span>
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination novedades-dest-pagination"></div>
            </div>
        </div>
    </div>

    <style>
        .news-card {
            display: block;
            color: inherit;
            text-decoration: none;
        }

        .news-card-image {
            display: block;
            height: 242px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .news-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .news-card:hover .news-card-image img {
            transform: scale(1.04);
        }

        .news-card-body {
            display: block;
            padding: 22px 20px 0;
        }

        .news-card-category {
            display: block;
            margin-bottom: 12px;
            color: #0B982C;
            font-size: 13px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
        }

        .news-card-title {
            display: block;
            min-height: 58px;
            margin-bottom: 12px;
            color: #404041;
            font-size: 26px;
            font-weight: 400;
            line-height: 1.1;
            transition: color .2s ease;
        }

        .news-card:hover .news-card-title {
            color: #52A028;
        }

        .news-card-description {
            display: block;
            min-height: 54px;
            color: #404041;
            font-size: 16px;
            font-weight: 400;
            line-height: 1.4;
        }

        .news-card-link {
            display: block;
            margin-top: 22px;
            color: #9b9b9b;
            font-size: 14px;
            font-weight: 400;
            line-height: 1;
            transition: color .2s ease;
        }

        .news-card:hover .news-card-link {
            color: #52A028;
        }

        @media (max-width: 1199px) {
            .novedades-dest-title {
                font-size: 26px !important;
            }
        }

        @media (max-width: 767px) {
            .novedades-dest-title {
                font-size: 22px !important;
            }

            .news-card-image {
                height: 230px;
            }

            .news-card-body {
                padding: 18px 20px 0;
            }

            .news-card-title {
                min-height: auto;
                font-size: 22px;
            }

            .news-card-description {
                min-height: auto;
                font-size: 15px;
            }
        }

        .novedadesDestacadasSwiper {
            overflow: hidden;
            position: relative;
        }

        .novedadesDestacadasSwiper .swiper-slide {
            width: 392px;
            flex-shrink: 0;
        }

        @media (min-width: 640px) and (max-width: 991px) {
            .novedadesDestacadasSwiper .swiper-slide {
                width: 82vw;
            }
        }

        @media (max-width: 639px) {
            .novedadesDestacadasSwiper {
                padding-bottom: 36px !important;
            }

            .novedadesDestacadasSwiper .swiper-slide {
                width: 100%;
            }
        }

        .novedadesDestacadasSwiper .swiper-pagination {
            display: none;
        }

        @media (max-width: 639px) {
            .novedadesDestacadasSwiper .swiper-pagination {
                display: block;
            }
        }

        .novedadesDestacadasSwiper .swiper-pagination-bullet {
            width: 43.912px;
            height: 6px;
            border-radius: 0;
            background: #eeeeee;
            opacity: 1;
            transition: background-color .2s ease;
        }

        .novedadesDestacadasSwiper .swiper-pagination-bullet-active {
            background: #c7c7c7;
        }
    </style>

    <script>
    (function() {
        let novedadesDestacadasSwiperInstance = null;

        function initNovedadesDestacadasSwiper() {
            if (novedadesDestacadasSwiperInstance) {
                novedadesDestacadasSwiperInstance.destroy(true, true);
                novedadesDestacadasSwiperInstance = null;
            }

            const swiperElement = document.querySelector('.novedadesDestacadasSwiper');
            if (!swiperElement || typeof Swiper === 'undefined') return;

            setTimeout(() => {
                novedadesDestacadasSwiperInstance = new Swiper('.novedadesDestacadasSwiper', {
                    slidesPerView: 'auto',
                    spaceBetween: 24,
                    speed: 400,
                    loop: true,
                    grabCursor: true,
                    observer: true,
                    observeParents: true,
                    slideToClickedSlide: false,
                    watchOverflow: true,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },
                    pagination: {
                        el: swiperElement.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                    breakpoints: {
                        0: {
                            spaceBetween: 12,
                            slidesPerView: 1,
                        },
                        640: {
                            spaceBetween: 16,
                            slidesPerView: 'auto',
                        },
                        768: {
                            spaceBetween: 20,
                            slidesPerView: 'auto',
                        },
                        1024: {
                            spaceBetween: 24,
                            slidesPerView: 'auto',
                        },
                    }
                });
            }, 150);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNovedadesDestacadasSwiper);
        } else {
            initNovedadesDestacadasSwiper();
        }

        document.addEventListener('livewire:navigated', initNovedadesDestacadasSwiper);

        document.addEventListener('livewire:navigating', () => {
            if (novedadesDestacadasSwiperInstance) {
                novedadesDestacadasSwiperInstance.destroy(true, true);
                novedadesDestacadasSwiperInstance = null;
            }
        });
    })();
    </script>
</section>
@endif
