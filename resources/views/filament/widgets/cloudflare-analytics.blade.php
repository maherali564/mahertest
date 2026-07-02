<div class="space-y-6" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-600 to-purple-800 p-5 text-white shadow-lg ring-1 ring-white/10">
            <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-white/10"></div>
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-purple-100">{{ __('filament.widgets.cloudflare_analytics.requests') }}</p>
                    <p class="text-3xl font-bold tracking-tight">{{ number_format($data['requests'] ?? 0) }}</p>
                </div>
                <div class="rounded-xl bg-white/15 p-2.5 backdrop-blur-sm">
                    <x-heroicon-o-arrow-path class="h-5 w-5" />
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-pink-600 to-pink-800 p-5 text-white shadow-lg ring-1 ring-white/10">
            <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-white/10"></div>
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-pink-100">{{ __('filament.widgets.cloudflare_analytics.visits') }}</p>
                    <p class="text-3xl font-bold tracking-tight">{{ number_format($data['visits'] ?? 0) }}</p>
                </div>
                <div class="rounded-xl bg-white/15 p-2.5 backdrop-blur-sm">
                    <x-heroicon-o-eye class="h-5 w-5" />
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 p-5 text-white shadow-lg ring-1 ring-white/10">
            <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-white/10"></div>
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-blue-100">{{ __('filament.widgets.cloudflare_analytics.bandwidth') }}</p>
                    <p class="text-3xl font-bold tracking-tight">{{ $data['bandwidth'] ? number_format($data['bandwidth'] / 1024 / 1024, 1) . ' GB' : '0 GB' }}</p>
                </div>
                <div class="rounded-xl bg-white/15 p-2.5 backdrop-blur-sm">
                    <x-heroicon-o-server class="h-5 w-5" />
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-green-600 to-green-800 p-5 text-white shadow-lg ring-1 ring-white/10">
            <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-white/10"></div>
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-green-100">{{ __('filament.widgets.cloudflare_analytics.cache') }}</p>
                    <p class="text-3xl font-bold tracking-tight">{{ number_format($data['cache'] ?? 0) }}</p>
                </div>
                <div class="rounded-xl bg-white/15 p-2.5 backdrop-blur-sm">
                    <x-heroicon-o-server-stack class="h-5 w-5" />
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 dark:border-gray-700">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                    <x-heroicon-o-globe-alt class="h-4 w-4" />
                </div>
                <h3 class="text-xs font-semibold text-gray-900 dark:text-white">{{ __('filament.widgets.cloudflare_analytics.top_countries') }}</h3>
            </div>
            <div class="px-5 py-3">
                @forelse (($data['countries'] ?? []) as $country)
                    <div class="flex items-center justify-between py-1.5">
                        <span class="flex h-6 w-6 items-center justify-center rounded bg-gray-50 text-[10px] font-bold uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-400">{{ $country['code'] ?? '?' }}</span>
                        <span class="rounded bg-purple-50 px-2 py-0.5 text-[11px] font-semibold text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">{{ number_format($country['requests'] ?? 0) }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center py-4 text-center">
                        <x-heroicon-o-globe-alt class="mb-1.5 h-6 w-6 text-gray-300 dark:text-gray-600" />
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('filament.widgets.cloudflare_analytics.no_data') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 dark:border-gray-700">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-pink-50 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400">
                    <x-heroicon-o-device-phone-mobile class="h-4 w-4" />
                </div>
                <h3 class="text-xs font-semibold text-gray-900 dark:text-white">{{ __('filament.widgets.cloudflare_analytics.devices') }}</h3>
            </div>
            <div class="space-y-1 px-5 py-3">
                @php $devices = $data['devices'] ?? []; @endphp
                <div class="flex items-center justify-between rounded-lg bg-blue-50 px-3 py-2 dark:bg-blue-900/10">
                    <span class="flex items-center gap-1.5 text-xs font-medium text-blue-700 dark:text-blue-300">
                        <x-heroicon-o-device-phone-mobile class="h-3.5 w-3.5" />
                        {{ __('filament.widgets.cloudflare_analytics.mobile') }}
                    </span>
                    <span class="text-xs font-bold text-blue-700 dark:text-blue-300">{{ number_format($devices['mobile'] ?? $devices['phone'] ?? 0) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-green-50 px-3 py-2 dark:bg-green-900/10">
                    <span class="flex items-center gap-1.5 text-xs font-medium text-green-700 dark:text-green-300">
                        <x-heroicon-o-computer-desktop class="h-3.5 w-3.5" />
                        {{ __('filament.widgets.cloudflare_analytics.desktop') }}
                    </span>
                    <span class="text-xs font-bold text-green-700 dark:text-green-300">{{ number_format($devices['desktop'] ?? $devices['pc'] ?? 0) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-purple-50 px-3 py-2 dark:bg-purple-900/10">
                    <span class="flex items-center gap-1.5 text-xs font-medium text-purple-700 dark:text-purple-300">
                        <x-heroicon-o-device-tablet class="h-3.5 w-3.5" />
                        {{ __('filament.widgets.cloudflare_analytics.tablet') }}
                    </span>
                    <span class="text-xs font-bold text-purple-700 dark:text-purple-300">{{ number_format($devices['tablet'] ?? $devices['tab'] ?? 0) }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 dark:border-gray-700">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                    <x-heroicon-o-code-bracket class="h-4 w-4" />
                </div>
                <h3 class="text-xs font-semibold text-gray-900 dark:text-white">{{ __('filament.widgets.cloudflare_analytics.status_codes') }}</h3>
            </div>
            <div class="grid grid-cols-2 gap-2 px-5 py-3">
                @php $codes = $data['status_codes'] ?? []; @endphp
                <div class="rounded-lg bg-green-50 p-2.5 text-center dark:bg-green-900/10">
                    <p class="text-sm font-bold text-green-600 dark:text-green-400">{{ number_format($codes['2xx'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-green-600/70 dark:text-green-400/70">2xx</p>
                </div>
                <div class="rounded-lg bg-blue-50 p-2.5 text-center dark:bg-blue-900/10">
                    <p class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ number_format($codes['3xx'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-blue-600/70 dark:text-blue-400/70">3xx</p>
                </div>
                <div class="rounded-lg bg-yellow-50 p-2.5 text-center dark:bg-yellow-900/10">
                    <p class="text-sm font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($codes['4xx'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-yellow-600/70 dark:text-yellow-400/70">4xx</p>
                </div>
                <div class="rounded-lg bg-red-50 p-2.5 text-center dark:bg-red-900/10">
                    <p class="text-sm font-bold text-red-600 dark:text-red-400">{{ number_format($codes['5xx'] ?? 0) }}</p>
                    <p class="text-[10px] font-medium text-red-600/70 dark:text-red-400/70">5xx</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-3.5 dark:border-gray-700">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                    <x-heroicon-o-link class="h-4 w-4" />
                </div>
                <h3 class="text-xs font-semibold text-gray-900 dark:text-white">{{ __('filament.widgets.cloudflare_analytics.top_pages') }}</h3>
            </div>
            <div class="px-5 py-3">
                @forelse (($data['paths'] ?? []) as $path)
                    <div class="flex items-center justify-between py-1.5">
                        <span class="truncate text-xs text-gray-700 dark:text-gray-300" title="{{ $path['path'] ?? $path['name'] ?? '' }}">{{ $path['path'] ?? $path['name'] ?? __('filament.widgets.cloudflare_analytics.unknown') }}</span>
                        <span class="shrink-0 rounded bg-blue-50 px-2 py-0.5 text-[11px] font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">{{ number_format($path['requests'] ?? $path['count'] ?? 0) }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center py-4 text-center">
                        <x-heroicon-o-link class="mb-1.5 h-6 w-6 text-gray-300 dark:text-gray-600" />
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('filament.widgets.cloudflare_analytics.no_data') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
