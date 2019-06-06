<?php

$lang = &$GLOBALS['TL_LANG']['tl_job'];

/**
 * Fields
 */
$lang['tstamp'][0] = 'Änderungsdatum';

// general
$lang['title'][0]                         = 'Titel';
$lang['title'][1]                         = 'Geben Sie hier bitte den Titel ein.';
$lang['date'][0]                          = 'Datum';
$lang['date'][1]                          = 'Bitte geben Sie das Datum gemäß des globalen Datumsformats ein.';
$lang['time'][0]                          = 'Zeit';
$lang['time'][1]                          = 'Bitte geben Sie die Uhrzeit gemäß des globalen Zeitformats ein.';
$lang['teaser'][0]                        = 'Teasertext';
$lang['teaser'][1]                        = 'Der Teasertext kann in einer Jobliste anstatt des ganzen Beitrags angezeigt werden. Ein "Weiterlesen …"-Link wird automatisch hinzugefügt.';
$lang['subheadline'][0]                   = 'Unterüberschrift';
$lang['subheadline'][1]                   = 'Hier können Sie eine Unterüberschrift eingeben.';
$lang['description'][0]                   = 'Beschreibung';
$lang['description'][1]                   = 'Geben Sie hier bitte eine Beschreibung ein.';
$lang['location'][0]                      = 'Ort';
$lang['location'][1]                      = 'Geben Sie hier bitte den Ort ein, an dem der Bewerber präsent sein muss.';
$lang['region'][0]                        = 'Region';
$lang['region'][1]                        = 'Geben Sie hier bitte die Region ein, in der der Bewerber präsent sein muss.';
$lang['addImage'][0]                      = 'Bild hinzufügen';
$lang['addImage'][1]                      = 'Wählen Sie diese Option, um ein Bild hinzuzufügen.';
$lang['singleSRC'][0]                     = 'Quell-Datei';
$lang['singleSRC'][1]                     = 'Wählen Sie hier eine Bilddatei aus.';
$lang['files'][0]                         = 'Anlagen';
$lang['files'][1]                         = 'Laden Sie hier bei Bedarf zusätzliche Dateien hoch.';
$lang['workingTime'][0]                   = 'Arbeitszeit';
$lang['workingTime'][1]                   = 'Wählen Sie hier die verfügbaren Arbeitszeitmodelle aus.';
$lang['levelsOfEducation'][0]             = 'Erforderliche Qualifikationen';
$lang['levelsOfEducation'][1]             = 'Wählen Sie hier die nötigen Qualifikationen aus.';
$lang['targets'][0]                       = 'Zielgruppe';
$lang['targets'][1]                       = 'Wählen Sie hier passende Zielgruppen aus.';
$lang['yearsOfProfessionalExperience'][0] = 'Jahre der Berufserfahrung';
$lang['yearsOfProfessionalExperience'][1] = 'Geben Sie hier an, wie viele Jahre Berufserfahrung der Bewerber mindestens vorweisen muss.';

// employer
$lang['employer'][0] = 'Arbeitgeber';
$lang['employer'][1] = 'Wählen Sie hier eine Firma/Institution aus.';

// published
$lang['published'][0] = 'Veröffentlichen';
$lang['published'][1] = 'Wählen Sie diese Option zum Veröffentlichen.';
$lang['start'][0]     = 'Anzeigen ab';
$lang['start'][1]     = 'Job erst ab diesem Tag auf der Webseite anzeigen.';
$lang['stop'][0]      = 'Anzeigen bis';
$lang['stop'][1]      = 'Job nur bis zu diesem Tag auf der Webseite anzeigen.';

/**
 * Legends
 */
$lang['title_legend']     = 'Titel';
$lang['date_legend']      = 'Datum und Zeit';
$lang['teaser_legend']    = 'Unterüberschrift und Teaser';
$lang['detail_legend']    = 'Stellenbeschreibung';
$lang['employer_legend']  = 'Arbeitgeber';
$lang['enclosure_legend'] = 'Anlagen';
$lang['publish_legend']   = 'Veröffentlichung';

/**
 * Reference
 */
$lang['reference'] = [
    \HeimrichHannot\JobBundle\Model\JobModel::WORKING_TIME_FULL_TIME                => 'Vollzeit',
    \HeimrichHannot\JobBundle\Model\JobModel::WORKING_TIME_PART_TIME                => 'Teilzeit',
    \HeimrichHannot\JobBundle\Model\JobModel::TARGET_PROFESSIONAL                   => 'Bewerber mit Berufserfahrung',
    \HeimrichHannot\JobBundle\Model\JobModel::TARGET_STUDENT                        => 'Student',
    \HeimrichHannot\JobBundle\Model\JobModel::TARGET_APPRENTICE                     => 'Auszubildender',
    \HeimrichHannot\JobBundle\Model\JobModel::TARGET_TRAINEE                        => 'Praktikant/Trainee',
    \HeimrichHannot\JobBundle\Model\JobModel::TARGET_SCHOOL_STUDENT                 => 'Schüler',
    \HeimrichHannot\JobBundle\Model\JobModel::EDUCATION_UNIVERSITY_GRADUATE         => 'Hochschulabsolvent',
    \HeimrichHannot\JobBundle\Model\JobModel::EDUCATION_COLLEGE_GRADUATE            => 'Fachhochschulabsolvent',
    \HeimrichHannot\JobBundle\Model\JobModel::EDUCATION_VOCATIONAL_ACADEMY_GRADUATE => 'BA-Absolvent',
    \HeimrichHannot\JobBundle\Model\JobModel::EDUCATION_COMPLETED_APPRENTICESHIP    => 'Abgeschlossene Berufsausbildung',
];

/**
 * Buttons
 */
$lang['new']      = ['Neuer Job', 'Job erstellen'];
$lang['edit']     = ['Job-Details bearbeiten', 'Job-Details ID %s bearbeiten'];
$lang['editMeta'] = ['Job bearbeiten', 'Job ID %s bearbeiten'];
$lang['copy']     = ['Job duplizieren', 'Job ID %s duplizieren'];
$lang['delete']   = ['Job löschen', 'Job ID %s löschen'];
$lang['toggle']   = ['Job veröffentlichen', 'Job ID %s veröffentlichen/verstecken'];
$lang['show']     = ['Job Details', 'Job-Details ID %s anzeigen'];
