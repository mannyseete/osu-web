{{--
    Copyright (c) ppy Pty Ltd <contact@ppy.sh>.

    This file is part of osu!web. osu!web is distributed with the hope of
    attracting more community contributions to the core ecosystem of osu!.

    osu!web is free software: you can redistribute it and/or modify
    it under the terms of the Affero GNU General Public License version 3
    as published by the Free Software Foundation.

    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See the GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
--}}
@php
    $backgroundExtraClass = $params['backgroundExtraClass'] ?? '';
    $backgroundImage = $params['backgroundImage'] ?? null;
    $headerExtraClass = $params['headerExtraClass'] ?? '';
    $links = $params['links'] ?? null;
    $linksBreadcrumb = $params['linksBreadcrumb'] ?? false;
    $theme = $params['theme'] ?? null;
    $showTitle = $params['showTitle'] ?? true;

    $linksElement = $linksBreadcrumb ? 'ol' : 'ul';
@endphp
<div class="
    header-v4
    {{ isset($theme) ? "header-v4--{$theme}" : '' }}
    {{ (auth()->check() && auth()->user()->isRestricted()) ? 'header-v4--restricted' : '' }}
    {{ $headerExtraClass }}
">
    <div class="header-v4__container header-v4__container--main">
        <div class="header-v4__bg-container">
            <div class="header-v4__bg {{ $backgroundExtraClass }}" {!! background_image($backgroundImage ?? null, false) !!}></div>
        </div>

        <div class="header-v4__content">
            @if ($showTitle || isset($titleAppend))
                <div class="header-v4__row header-v4__row--title">
                    @if ($showTitle)
                        <div class="header-v4__icon"></div>
                        <div class="header-v4__title">
                            {{ title_header() }}
                        </div>
                    @endif

                    {{ $titleAppend ?? null }}
                </div>
            @endif

            {{ $contentAppend ?? null }}
        </div>
    </div>
    @if ($links !== null && count($links) > 0)
        <div class="header-v4__container">
            <div class="header-v4__content">
                <div class="header-v4__row header-v4__row--bar">
                    <{!! $linksElement !!}
                        class="header-nav-v4 header-nav-v4--{{ $linksBreadcrumb ? 'breadcrumb' : 'list' }}"
                    >
                        @foreach ($links as $link)
                            <li class="header-nav-v4__item">
                                @if (isset($link['url']))
                                    <a
                                        class="
                                            header-nav-v4__link
                                            {{ ($link['active'] ?? false) ? 'header-nav-v4__link--active' : '' }}
                                        "
                                        href="{{ $link['url'] }}"
                                    >
                                        {{ $link['title'] }}
                                    </a>
                                @else
                                    <span class="header-nav-v4__text">{{ $link['title'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </{!! $linksElement !!}>

                    {{ $navAppend ?? null }}
                </div>
            </div>
        </div>
    @endif
</div>
