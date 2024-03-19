<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\JobBundle\Model;

use Contao\Model;

class JobModel extends Model
{
    const WORKING_TIME_FULL_TIME = 'full_time';
    const WORKING_TIME_PART_TIME = 'part_time';

    const WORKING_TIMES = [
        self::WORKING_TIME_FULL_TIME,
        self::WORKING_TIME_PART_TIME,
    ];

    const TARGET_PROFESSIONAL = 'professional';
    const TARGET_STUDENT = 'student';
    const TARGET_APPRENTICE = 'apprentice';
    const TARGET_TRAINEE = 'trainee';
    const TARGET_SCHOOL_STUDENT = 'school_student';

    const TARGETS = [
        self::TARGET_PROFESSIONAL,
        self::TARGET_STUDENT,
        self::TARGET_APPRENTICE,
        self::TARGET_TRAINEE,
        self::TARGET_SCHOOL_STUDENT,
    ];

    const EDUCATION_UNIVERSITY_GRADUATE = 'university_graduate';
    const EDUCATION_COLLEGE_GRADUATE = 'college_graduate';
    const EDUCATION_VOCATIONAL_ACADEMY_GRADUATE = 'vocational_academy_graduate';
    const EDUCATION_COMPLETED_APPRENTICESHIP = 'complete_apprenticeship';

    const LEVELS_OF_EDUCATION = [
        self::EDUCATION_UNIVERSITY_GRADUATE,
        self::EDUCATION_COLLEGE_GRADUATE,
        self::EDUCATION_VOCATIONAL_ACADEMY_GRADUATE,
        self::EDUCATION_COMPLETED_APPRENTICESHIP,
    ];
    protected static $strTable = 'tl_job';
}
