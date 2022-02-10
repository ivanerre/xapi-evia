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

namespace src\transformer\utils\extensions;
defined('MOODLE_INTERNAL') || die();
use src\transformer\utils as utils;

function info(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $grade = $repo->read_record_by_id($event->objecttable, $event->objectid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $assignment = $repo->read_record_by_id('assign', $grade->assignment);
    $assignmentBridge =  $repo->read_record_sql('SELECT * FROM {course_modules} course_modules WHERE course_modules.course = :course AND  course_modules.instance = :instance', ['course' => intval($event->courseid), 'instance' => intval($grade->assignment)]); 
    $tags = $repo->read_records_sql('SELECT tag.id, tag.name FROM {tag} tag INNER JOIN {tag_instance} taginstance ON taginstance.tagid = tag.id WHERE taginstance.itemtype = :itemtype AND  taginstance.itemid = :itemid',['itemtype' => 'course_modules', 'itemid' => intval($assignmentBridge->id)]);
    $tags_ids = array();
    $tags_names = array();

    foreach ($tags as $key => $tag) {
        array_push($tags_ids, intval($tag->id));
        array_push($tags_names, $tag->name);
    }

    return [
        'http://lrs.learninglocker.net/define/extensions/info' => [
            $config['source_url'] => $config['source_version'],
            $config['plugin_url'] => $config['plugin_version'],
            'event_name' => $event->eventname,
            'event_function' => $config['event_function'],
        ],
        'http://id.tincanapi.com/extension/competence-id' => $tags_ids,//$tags_ids,
        'http://id.tincanapi.com/extension/competence-map-id' => $tags_names
    ];
}
