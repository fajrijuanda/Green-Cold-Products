@php
    use Illuminate\Support\Facades\Route;
    $configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- ! Hide app brand if navbar-full -->
    @if (!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                <div class="logo-container">
                    <span class="app-brand-logo demo" style="margin-left: -1px;">@include('_partials.macros', ['height' => 50])</span>
                </div>
                <div class="text-container">
                    @php
                        $templateName = explode(' ', config('variables.templateName'));
                    @endphp
                    @foreach ($templateName as $line)
                        <span class="app-brand-text demo menu-text fw-bold">{{ $line }}</span>
                    @endforeach
                </div>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
            </a>
        </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $menu)
            {{-- Tambahkan pengecekan untuk admin --}}
            @if (isset($menu->slug) && $menu->slug === 'users' && auth()->user()->role !== 'admin')
                @continue
            @endif

            {{-- Menu headers --}}
            @if (isset($menu->menuHeader))
                <li class="menu-header small">
                    <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
                </li>
            @else
                {{-- Active menu method --}}
                @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();
                    $currentUrl = request()->path(); // Ambil URL saat ini

                    // Periksa slug tunggal atau array dari slugs
                    if (isset($menu->slugs) && is_array($menu->slugs)) {
                        foreach ($menu->slugs as $slug) {
                            // Periksa jika slug cocok dengan currentRouteName atau URL yang mengandung slug
                            if ($currentRouteName === $slug || str_contains($currentUrl, trim($slug, '/'))) {
                                $activeClass = 'active open';
                                break;
                            }
                        }
                    } elseif ($currentRouteName === $menu->slug) {
                        $activeClass = 'active';
                    } elseif (isset($menu->submenu)) {
                        if (is_array($menu->slug)) {
                            foreach ($menu->slug as $slug) {
                                if (str_contains($currentUrl, trim($slug, '/'))) {
                                    $activeClass = 'active open';
                                }
                            }
                        } else {
                            if (str_contains($currentUrl, trim($menu->slug, '/'))) {
                                $activeClass = 'active open';
                            }
                        }
                    }
                @endphp


                {{-- Main menu --}}
                <li class="menu-item {{ $activeClass }}">
                    <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                        class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                        @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>
                        @isset($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @endisset
                        <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                        @isset($menu->badge)
                            <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
                        @endisset
                    </a>

                    {{-- Submenu --}}
                    @isset($menu->submenu)
                        @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                    @endisset
                </li>
            @endif
        @endforeach
    </ul>
</aside>
