<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace theme_wwu2019;

use core\persistent;

class cookie_consent_allocation extends persistent {

    const TABLE = "theme_wwu2019_cookie_consent";

    protected static function define_properties() {
        return array(
            'userid' => array(
                'type' => PARAM_INT,
                'message' => new \lang_string('invaliduserid', 'error')
            ),
            'consentdate' => array(
                'type' => PARAM_INT,
                'message' => new \lang_string('invalidconsentdate', 'theme_wwu2019')
            )
        );
    }
}
