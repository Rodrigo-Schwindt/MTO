<div
    x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 50)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 transform -translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
>
    <section class="relative w-full h-[450px] max-[1199px]:h-[340px] max-[767px]:h-[290px] max-[639px]:h-[250px] overflow-hidden">
        @if($pageData?->image_banner)
            <img src="{{ Storage::url($pageData->image_banner) }}"
                 alt="Presupuesto"
                 class="absolute inset-0 w-full h-full object-cover">
        @else
            <div class="absolute inset-0 bg-slate-700"></div>
        @endif

        <div
            class="absolute inset-0"
            style="background: linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%), linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%), linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);"
        ></div>

        <div class="relative z-10 max-w-[1224px] mx-auto h-full px-4 lg:px-0 flex flex-col pt-[94px] max-[767px]:pt-[74px] max-[639px]:pt-[64px]">
            <nav class="budget-breadcrumb text-white text-[12px] leading-[150%] flex items-center gap-1 mb-[148px] max-[1199px]:mb-[85px] max-[767px]:mb-[76px] max-[639px]:mb-[60px] max-[1199px]:flex-wrap">
                <a wire:navigate href="{{ url('/') }}" class="font-bold drop-shadow">Inicio</a>
                <span class="drop-shadow">›</span>
                <span class="drop-shadow">Presupuesto</span>
            </nav>

            <h1 class="budget-h1 text-white text-[40px] font-bold leading-none uppercase max-[767px]:text-[32px] drop-shadow-[0_6px_20px_rgba(0,0,0,0.55)]">
                Solicitar Presupuesto
            </h1>
        </div>
    </section>

    <section class="budget-section">
        <form wire:submit.prevent="submit" class="budget-form">
            <h2>Datos de contacto</h2>

            <div class="budget-grid">
                <label class="budget-field">
                    <span>Nombre y Apellido</span>
                    <input type="text" wire:model.blur="name">
                    @error('name') <small>{{ $message }}</small> @enderror
                </label>

                <label class="budget-field">
                    <span>Email*</span>
                    <input type="email" wire:model.blur="email">
                    @error('email') <small>{{ $message }}</small> @enderror
                </label>

                <label class="budget-field">
                    <span>Telefono*</span>
                    <input type="text" wire:model.blur="phone">
                    @error('phone') <small>{{ $message }}</small> @enderror
                </label>

                <label class="budget-field">
                    <span>Celular*</span>
                    <input type="text" wire:model.blur="mobile">
                    @error('mobile') <small>{{ $message }}</small> @enderror
                </label>

                <label class="budget-field">
                    <span>Provincia*</span>
                    <input type="text" wire:model.blur="province">
                    @error('province') <small>{{ $message }}</small> @enderror
                </label>

                <label class="budget-field">
                    <span>Localidad*</span>
                    <input type="text" wire:model.blur="locality">
                    @error('locality') <small>{{ $message }}</small> @enderror
                </label>
            </div>

            <h2 class="budget-consult-title">Consulta</h2>

            <div class="budget-grid">
                <div class="budget-field budget-cs-field"
                     x-data="{
                         open: false, q: '',
                         selected: @js($service ?? ''),
                         options: @js($services->pluck('title')->values()->all()),
                         get filtered() { const lq = this.q.toLowerCase(); return this.q ? this.options.filter(o => o.toLowerCase().includes(lq)) : this.options; },
                         toggle() { this.open = !this.open; if (this.open) this.$nextTick(() => this.$refs.q.focus()); },
                         pick(opt) { this.selected = opt; this.$wire.set('service', opt); this.open = false; this.q = ''; }
                     }"
                     @click.outside="open = false; q = ''">
                    <span>Servicio</span>
                    <button type="button" class="budget-cs-trigger" @click="toggle()" @keydown.escape="open = false; q = ''">
                        <span class="budget-cs-val" x-text="selected"></span>
                        <span class="budget-chevron" :class="{ 'budget-chevron-open': open }" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M6 9L12 15L18 9" stroke="#C5C5C5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </button>
                    <div class="budget-cs-drop" x-show="open" x-cloak @click.stop>
                        <div class="budget-cs-search-wrap">
                            <input type="text" class="budget-cs-search" x-model="q" x-ref="q" placeholder="Buscar..." autocomplete="off" @keydown.escape="open = false; q = ''">
                        </div>
                        <ul class="budget-cs-list">
                            <template x-for="opt in filtered" :key="opt">
                                <li class="budget-cs-item" :class="{ 'budget-cs-active': opt === selected }" @click="pick(opt)" x-text="opt"></li>
                            </template>
                            <li class="budget-cs-no-results" x-show="filtered.length === 0">Sin resultados</li>
                        </ul>
                    </div>
                    @error('service') <small>{{ $message }}</small> @enderror
                </div>

                <div class="budget-field budget-cs-field"
                     x-data="{
                         open: false, q: '',
                         selected: @js($systemType ?? ''),
                         options: @js($systemTypes->pluck('title')->values()->all()),
                         get filtered() { const lq = this.q.toLowerCase(); return this.q ? this.options.filter(o => o.toLowerCase().includes(lq)) : this.options; },
                         toggle() { this.open = !this.open; if (this.open) this.$nextTick(() => this.$refs.q.focus()); },
                         pick(opt) { this.selected = opt; this.$wire.set('systemType', opt); this.open = false; this.q = ''; }
                     }"
                     @click.outside="open = false; q = ''">
                    <span>Tipo de sistema</span>
                    <button type="button" class="budget-cs-trigger" @click="toggle()" @keydown.escape="open = false; q = ''">
                        <span class="budget-cs-val" x-text="selected"></span>
                        <span class="budget-chevron" :class="{ 'budget-chevron-open': open }" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M6 9L12 15L18 9" stroke="#C5C5C5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </button>
                    <div class="budget-cs-drop" x-show="open" x-cloak @click.stop>
                        <div class="budget-cs-search-wrap">
                            <input type="text" class="budget-cs-search" x-model="q" x-ref="q" placeholder="Buscar..." autocomplete="off" @keydown.escape="open = false; q = ''">
                        </div>
                        <ul class="budget-cs-list">
                            <template x-for="opt in filtered" :key="opt">
                                <li class="budget-cs-item" :class="{ 'budget-cs-active': opt === selected }" @click="pick(opt)" x-text="opt"></li>
                            </template>
                            <li class="budget-cs-no-results" x-show="filtered.length === 0">Sin resultados</li>
                        </ul>
                    </div>
                    @error('systemType') <small>{{ $message }}</small> @enderror
                </div>

                <div class="budget-field budget-cs-field"
                     x-data="{
                         open: false, q: '',
                         selected: @js($equipment ?? ''),
                         options: @js($equipmentProducts->pluck('title')->values()->all()),
                         get filtered() { const lq = this.q.toLowerCase(); return this.q ? this.options.filter(o => o.toLowerCase().includes(lq)) : this.options; },
                         toggle() { this.open = !this.open; if (this.open) this.$nextTick(() => this.$refs.q.focus()); },
                         pick(opt) { this.selected = opt; this.$wire.set('equipment', opt); this.open = false; this.q = ''; }
                     }"
                     @click.outside="open = false; q = ''">
                    <span>Equipamiento</span>
                    <button type="button" class="budget-cs-trigger" @click="toggle()" @keydown.escape="open = false; q = ''">
                        <span class="budget-cs-val" x-text="selected"></span>
                        <span class="budget-chevron" :class="{ 'budget-chevron-open': open }" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M6 9L12 15L18 9" stroke="#C5C5C5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </button>
                    <div class="budget-cs-drop" x-show="open" x-cloak @click.stop>
                        <div class="budget-cs-search-wrap">
                            <input type="text" class="budget-cs-search" x-model="q" x-ref="q" placeholder="Buscar..." autocomplete="off" @keydown.escape="open = false; q = ''">
                        </div>
                        <ul class="budget-cs-list">
                            <template x-for="opt in filtered" :key="opt">
                                <li class="budget-cs-item" :class="{ 'budget-cs-active': opt === selected }" @click="pick(opt)" x-text="opt"></li>
                            </template>
                            <li class="budget-cs-no-results" x-show="filtered.length === 0">Sin resultados</li>
                        </ul>
                    </div>
                    @error('equipment') <small>{{ $message }}</small> @enderror
                </div>

                <label class="budget-field budget-file-field">
                    <span>Archivo adjunto</span>
                    <span class="budget-file-line">{{ $attachment ? $attachment->getClientOriginalName() : '' }}</span>
                    <input type="file" wire:model="attachment">
                    <span class="budget-upload" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M12 3V15M7 8L12 3L17 8M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="#C5C5C5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                    </span>
                    @error('attachment') <small>{{ $message }}</small> @enderror
                </label>

                <label class="budget-field budget-message">
                    <span>Mensaje</span>
                    <textarea wire:model.blur="message"></textarea>
                    @error('message') <small>{{ $message }}</small> @enderror
                </label>

                <div class="budget-submit-column">
                    <p>*Campos obligatorios</p>
                    <button type="submit" wire:loading.attr="disabled" wire:target="submit,attachment">
                        <span wire:loading.remove wire:target="submit">Enviar solicitud</span>
                        <span wire:loading wire:target="submit">Enviando...</span>
                    </button>
                </div>
            </div>
        </form>
    </section>

    <style>
        .budget-section {
            background: #fff;
            padding: 74px 0 96px;
        }

        .budget-form {
            max-width: 1224px;
            margin: 0 auto;
        }

        .budget-form h2 {
            margin: 0 0 36px;
            color: #404041;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.15;
        }

        .budget-consult-title {
            margin-top: 34px !important;
        }

        .budget-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            column-gap: 72px;
            row-gap: 30px;
        }

        .budget-field {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 11px;
            color: #404041;
            font-size: 16px;
            line-height: 1;
        }

        .budget-field input,
        .budget-field select,
        .budget-field textarea {
            width: 100%;
            border: 0;
            border-bottom: 1px dotted #a8a8a8;
            border-radius: 0;
            padding: 0 34px 12px 0;
            color: #1f1f1f;
            font: inherit;
            outline: none;
            background: transparent;
            box-shadow: none;
        }

        .budget-field select {
            appearance: none;
            cursor: pointer;
        }

        .budget-field textarea {
            min-height: 140px;
            resize: vertical;
        }

        .budget-field input:focus,
        .budget-field select:focus,
        .budget-field textarea:focus {
            border-bottom-color: #52A028;
            box-shadow: none;
        }

        .budget-field small {
            color: #dc2626;
            font-size: 12px;
            line-height: 1.2;
        }

        .budget-chevron,
        .budget-upload {
            position: absolute;
            right: 18px;
            top: 26px;
            color: #b8b8b8;
            pointer-events: none;
            transition: transform 0.2s ease;
        }

        .budget-chevron-open {
            transform: rotate(180deg);
        }

        /* Custom select */
        [x-cloak] { display: none !important; }

        .budget-cs-field {
            position: relative;
        }

        .budget-cs-trigger {
            width: 100%;
            border: 0;
            border-bottom: 1px dotted #a8a8a8;
            border-radius: 0;
            padding: 0 34px 12px 0;
            background: transparent;
            text-align: left;
            cursor: pointer;
            font: inherit;
            font-size: 16px;
            color: #1f1f1f;
            display: block;
            outline: none;
        }

        .budget-cs-trigger:focus {
            border-bottom-color: #52A028;
        }

        .budget-cs-val:empty::after {
            content: '\00a0';
        }

        .budget-cs-drop {
            position: absolute;
            top: calc(100% + 2px);
            left: 0;
            right: 0;
            z-index: 200;
            background: #fff;
            border: 1px solid #e8e8e8;
            box-shadow: 0 6px 20px rgba(0,0,0,.10);
            display: flex;
            flex-direction: column;
            max-height: 260px;
        }

        .budget-cs-search-wrap {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
            flex-shrink: 0;
        }

        .budget-cs-search {
            width: 100%;
            border: 0;
            border-bottom: 1px dotted #a8a8a8;
            border-radius: 0;
            padding: 0 0 8px;
            font: inherit;
            font-size: 14px;
            color: #1f1f1f;
            background: transparent;
            outline: none;
        }

        .budget-cs-search:focus {
            border-bottom-color: #52A028;
        }

        .budget-cs-search::placeholder {
            color: #c5c5c5;
        }

        .budget-cs-list {
            list-style: none;
            margin: 0;
            padding: 6px 0;
            overflow-y: auto;
            flex: 1;
        }

        .budget-cs-item {
            padding: 10px 16px;
            cursor: pointer;
            font-size: 15px;
            color: #404041;
            line-height: 1.2;
            transition: background .12s;
        }

        .budget-cs-item:hover {
            background: #f7f7f7;
        }

        .budget-cs-active {
            color: #52A028;
            font-weight: 600;
        }

        .budget-cs-no-results {
            padding: 10px 16px;
            font-size: 14px;
            color: #a8a8a8;
        }

        .budget-file-field {
            cursor: pointer;
        }

        .budget-file-field input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .budget-file-line {
            padding: 0 34px 12px 0;
            border-bottom: 1px dotted #a8a8a8;
            color: #1f1f1f;
            font-size: 16px;
            line-height: 1;
            min-height: 29px;
        }

        .budget-message {
            grid-column: 1 / 2;
        }

        .budget-submit-column {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
        }

        .budget-submit-column p {
            margin: 0;
            color: #404041;
            font-size: 15px;
            line-height: 1.3;
        }

        .budget-submit-column button {
            width: 220px;
            min-height: 42px;
            border: 0;
            background: #52A028;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
            transition: background .2s ease;
        }

        .budget-submit-column button:hover {
            background: #439e33;
        }

        .budget-submit-column button:disabled {
            opacity: .7;
            cursor: wait;
        }

        @media (max-width: 1199px) {
            .budget-banner-inner,
            .budget-form {
                padding-left: 24px;
                padding-right: 24px;
            }

            .budget-grid {
                column-gap: 44px;
            }
        }

        @media (max-width: 767px) {
            .budget-banner {
                height: 290px;
            }

            .budget-banner h1 {
                font-size: 32px;
            }

            .budget-section {
                padding: 56px 0 72px;
            }

            .budget-form h2 {
                font-size: 28px;
            }

            .budget-grid {
                grid-template-columns: 1fr;
                gap: 26px;
            }

            .budget-message {
                grid-column: auto;
            }

            .budget-submit-column {
                align-items: stretch;
                flex-direction: column;
            }

            .budget-submit-column button {
                width: 100%;
            }
        }

        @media (max-width: 639px) {
            .budget-banner-inner,
            .budget-form {
                padding-left: 16px;
                padding-right: 16px;
            }
        }

        @keyframes bud-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .budget-breadcrumb {
            animation: bud-fade-up 0.65s ease 0.38s both;
        }

        .budget-h1 {
            animation: bud-fade-up 0.65s ease 0.52s both;
        }

        .budget-section {
            animation: bud-fade-up 0.7s ease 0.6s both;
        }
    </style>
</div>
