<?php

/**
 *    Copyright (c) ppy Pty Ltd <contact@ppy.sh>.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Http\Controllers;

use App;
use App\Http\Middleware\VerifyUserAlways;
use App\Libraries\LocaleMeta;
use App\Models\Log;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Request;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
    }

    protected function formatValidationErrors(Validator $validator)
    {
        return ['validation_error' => $validator->errors()->getMessages()];
    }

    protected function log($params)
    {
        $params['user_id'] = Auth::user()->user_id ?? 0;
        $params['log_ip'] = Request::ip();
        $params['log_time'] = Carbon::now();

        Log::log($params);
    }

    protected function login($user, $remember = false)
    {
        cleanup_cookies();

        session()->flush();
        session()->regenerateToken();
        session()->put('requires_verification', VerifyUserAlways::isRequired($user));
        Auth::login($user, $remember);
        session()->migrate(true, Auth::user()->user_id);
    }

    protected function logout()
    {
        logout();
    }

    protected function locale()
    {
        return LocaleMeta::sanitizeCode(request('locale')) ?? App::getLocale();
    }
}
