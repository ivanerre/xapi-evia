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

namespace src\transformer\events\mod_assign;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function assignment_submitted(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $assignmentsubmission = $repo->read_record_by_id('assign_submission', $event->objectid);
    $assignment = $repo->read_record_by_id('assign', $assignmentsubmission->assignment);
    $assignmentBridge =  $repo->read_record_sql('SELECT * FROM {course_modules} course_modules WHERE course_modules.course = :course AND  course_modules.instance = :instance', ['course' => intval($event->courseid), 'instance' => intval($grade->assignment)]); 
    $tags = $repo->read_records_sql('SELECT tag.id, tag.name FROM {tag} tag INNER JOIN {tag_instance} taginstance ON taginstance.tagid = tag.id WHERE taginstance.itemtype  = :itemtype AND  taginstance.itemid = :itemid', ['itemtype' => 'course_modules', 'itemid' => intval($assignmentBridge->id)]);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://activitystrea.ms/schema/1.0/submit',
            'display' => [
                $lang => 'submitted'
            ],
        ],
        'object' => utils\get_activity\course_assignment($config, $event->contextinstanceid, $assignment->name, $lang),
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => utils\extensions\base($config, $event, $course),
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                ],
                'category' => [
                    utils\get_activity\source($config)
                ]
            ],
        ]
    ]];
}
