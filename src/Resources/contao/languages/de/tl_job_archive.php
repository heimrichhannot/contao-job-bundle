<?php

$lang = &$GLOBALS['TL_LANG']['tl_job_archive'];

/**
 * Fields
 */
$lang['tstamp'][0]         = 'Änderungsdatum';
$lang['title'][0]          = 'Titel';
$lang['title'][1]          = 'Geben Sie hier bitte den Titel ein.';
$lang['memberContacts'][0] = 'Ansprechpartner (Mitglied)';
$lang['memberContacts'][1] = 'Wählen Sie hier die Mitglieder aus, die die Firma bearbeiten dürfen. Die Logik für die Prüfung der Berechtigung muss im Frontend durch das Modul zur Frontend-Bearbeitung (bspw. <i>heimrichhannot/contao-frontendedit</i>) umgesetzt werden.';

/**
 * Legends
 */
$lang['general_legend'] = 'Allgemeine Einstellungen';
$lang['publish_legend'] = 'Veröffentlichung';

/**
 * Buttons
 */
$lang['new']    = ['Neues Jobarchiv', 'Jobarchiv erstellen'];
$lang['edit']   = ['Jobarchiv bearbeiten', 'Jobarchiv ID %s bearbeiten'];
$lang['copy']   = ['Jobarchiv duplizieren', 'Jobarchiv ID %s duplizieren'];
$lang['delete'] = ['Jobarchiv löschen', 'Jobarchiv ID %s löschen'];
$lang['show']   = ['Jobarchiv Details', 'Jobarchiv-Details ID %s anzeigen'];
$lang['toggle'] = ['Jobarchiv veröffentlichen', 'Jobarchiv ID %s veröffentlichen/verstecken'];
