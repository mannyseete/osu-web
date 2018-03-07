{{--
    Copyright 2015-2017 ppy Pty. Ltd.

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
// input field name mappings for view recycling.
// pass in $fields to set; set a field to false to remove it.
// TODO: hopefully this can be temporary ಠ_ಠ.
$fieldDefaults = [
    'forumId' => 'forum_id',
    'topicId' => 'topic_id',
    'user' => 'username',
    'includeSubforums' => 'forum_children',
];

if (isset($fields)) {
    $fields = array_merge($fieldDefaults, $fields);
} else {
    $fields = $fieldDefaults;
}
@endphp

<div class="search-advanced-forum-post">
    @if ($fields['user'] !== false)
        <label class="search-advanced-forum-post__input-group">
            <div class="search-advanced-forum-post__label">
                {{ trans('home.search.forum_post.label.username') }}
            </div>

            <input
                name="{{ $fields['user'] }}"
                value="{{ request($fields['user']) }}"
                class="search-advanced-forum-post__input search-advanced-forum-post__input--text"
            >
        </label>
    @endif

    {{-- FIXME: remove querystring check? --}}
    @if ($fields['topicId'] !== false && present(request($fields['topicId'])))
        <label class="search-advanced-forum-post__input-group">
            <div class="search-advanced-forum-post__label">
                {{ trans('home.search.forum_post.label.topic_id') }}
            </div>

            <input
                name="{{ $fields['topicId'] }}"
                value="{{ request($fields['topicId']) }}"
                class="search-advanced-forum-post__input search-advanced-forum-post__input--text"
            >
        </label>
    @elseif ($fields['forumId'] !== false)
        <label class="search-advanced-forum-post__input-group">
            <div class="search-advanced-forum-post__label">
                {{ trans('home.search.forum_post.label.forum') }}
            </div>

            <div class="search-advanced-forum-post__input-container">
                <select
                    name="{{ $fields['forumId'] }}"
                    class="search-advanced-forum-post__input"
                >
                    <option value="">
                        {{ trans('home.search.forum_post.all') }}
                    </option>

                    @foreach (App\Models\Forum\Forum::displayList()->get() as $forum)
                        @if (priv_check('ForumView', $forum)->can())
                            <option
                                value="{{ $forum->getKey() }}"
                                {{ $forum->getKey() === get_int(request($fields['forumId'])) ? 'selected' : '' }}
                            >
                                {{ str_repeat('–', $forum->currentDepth()) }}
                                {{ $forum->forum_name }}
                            </option>
                        @endif
                    @endforeach
                </select>

                <div class="search-advanced-forum-post__dropdown-arrow">
                    <span class="fa fa-chevron-down"></span>
                </div>
            </div>
        </label>

        <label class="search-advanced-forum-post__input-group">
            <div class="osu-checkbox">
                <input
                    type="checkbox"
                    name="{{ $fields['includeSubforums'] }}"
                    {{ request($fields['includeSubforums']) ? 'checked' : '' }}
                    class="osu-checkbox__input"
                >
                <span class="osu-checkbox__box"></span>
                <span class="osu-checkbox__tick">
                    <span class="fa fa-check"></span>
                </span>

            </div>

            {{ trans('home.search.forum_post.label.forum_children') }}
        </label>
    @endif

    <div class="search-advanced-forum-post__input-group search-advanced-forum-post__input-group--buttons">
        <button class="btn-osu-big btn-osu-big--search-advanced">
            <div class="btn-osu-big__content">
                <div class="btn-osu-big__left">
                    {{ trans('home.search.button') }}
                </div>

                <div class="btn-osu-big__icon">
                    <span class="fa fa-search"></span>
                </div>
            </div>
        </button>

        <button type="button" class="btn-osu-big btn-osu-big--search-advanced js-search--advanced-forum-post-reset">
            <div class="btn-osu-big__content">
                <div class="btn-osu-big__left">
                    {{ trans('common.buttons.reset') }}
                </div>

                <div class="btn-osu-big__icon">
                    <span class="fa fa-refresh"></span>
                </div>
            </div>
        </button>
    </div>
</div>
