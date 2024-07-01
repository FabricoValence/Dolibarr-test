<?php
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/adherents/class/adherent.class.php';

// Загрузка языковых файлов
$langs->loadLangs(array("members", "companies"));

// Ограничение доступа
$result = restrictedArea($user, 'adherent');

// Получение параметров сортировки и фильтрации
$sortfield = GETPOST('sortfield', 'alpha');
$sortorder = GETPOST('sortorder', 'alpha');
$filter_date = GETPOST('filter_date', 'alpha');
$filter_month = GETPOST('filter_month', 'alpha');
$filter_year = GETPOST('filter_year', 'alpha');

if (!$sortfield) $sortfield = 'entry_hour';
if (!$sortorder) $sortorder = 'ASC';


$sql = "SELECT le.entry_id, a.lastname, a.firstname, a.card_uid, DATE(le.entry_time) AS entry_date, TIME(le.entry_time) AS entry_hour
      FROM ".MAIN_DB_PREFIX."list_entry le
      JOIN ".MAIN_DB_PREFIX."adherent a ON le.rowid = a.rowid
      WHERE 1=1";


if ($filter_date || $filter_month || $filter_year) {
    if ($filter_date) {
        $sql .= " AND DAY(le.entry_time) = ".$db->escape($filter_date);
    }
    if ($filter_month) {
        $sql .= " AND MONTH(le.entry_time) = ".$db->escape($filter_month);
    }
    if ($filter_year) {
        $sql .= " AND YEAR(le.entry_time) = ".$db->escape($filter_year);
    }
} else {
    
    $sql .= " AND DATE(le.entry_time) = CURDATE()";
}

$sql .= " ORDER BY ".$db->escape($sortfield)." ".$db->escape($sortorder);
$resql = $db->query($sql);
if ($resql) {
    llxHeader('', $langs->trans("List de passage"));

    print '<h1>'.$langs->trans("List de passage").'</h1>';

    print '<form method="GET" action="'.$_SERVER["PHP_SELF"].'">';
    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre">';
    print '<td><a href="?sortfield=entry_id&sortorder='.($sortfield == 'entry_id' && $sortorder == 'ASC' ? 'DESC' : 'ASC').'">'.$langs->trans("ID").'</a></td>';
    print '<td><a href="?sortfield=lastname&sortorder='.($sortfield == 'lastname' && $sortorder == 'ASC' ? 'DESC' : 'ASC').'">'.$langs->trans("Nom").'</a></td>';
    print '<td><a href="?sortfield=firstname&sortorder='.($sortfield == 'firstname' && $sortorder == 'ASC' ? 'DESC' : 'ASC').'">'.$langs->trans("Prenom").'</a></td>';
    print '<td>'.$langs->trans("CardUID").'</td>';
    print '<td><a href="?sortfield=entry_hour&sortorder='.($sortfield == 'entry_hour' && $sortorder == 'ASC' ? 'DESC' : 'ASC').'">'.$langs->trans("Heure").'</a></td>';
    print '<td>'.$langs->trans("Date").' ';
    print '<input type="text" name="filter_date" size="2" value="'.$filter_date.'">';
    print '/<input type="text" name="filter_month" size="2" value="'.$filter_month.'">';
    print '/<input type="text" name="filter_year" size="4" value="'.$filter_year.'">';
    print '<input type="submit" value="'.$langs->trans("Filter").'">';
    print '</td>';
    print '</tr>';
    print '</form>';

    while ($obj = $db->fetch_object($resql)) {
        print '<tr>';
        print '<td>'.$obj->entry_id.'</td>';
        print '<td>'.$obj->lastname.'</td>';
        print '<td>'.$obj->firstname.'</td>';
        print '<td>'.$obj->card_uid.'</td>';
        print '<td>'.dol_print_date($db->jdate($obj->entry_date), 'day').'</td>';
        print '<td>'.$obj->entry_hour.'</td>';
        print '</tr>';
    }

    print '</table>';

    $db->free($resql);
} else {
    dol_print_error($db);
}

llxFooter();
$db->close();
?>


