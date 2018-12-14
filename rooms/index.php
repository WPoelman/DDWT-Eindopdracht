<?php
/**
 * Controller
 * Date: 4-12-2018
 * Time: 15:46
 */

include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'roomturbo', 'roomturbo', 'roomturbo');

$template = Array(
    1 => Array('name' => 'Home','url' => '/DDWT-Eindopdracht/rooms/'),
    2 => Array('name' => 'Overview','url' => '/DDWT-Eindopdracht/rooms/overview/')
);

/* Landing page */
if (new_route('/DDWT-Eindopdracht/rooms/', 'get')) {
    /* Page info */
    $page_title = 'Home';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/DDWT-Eindopdracht/rooms/', True)
    ]);
    $active_id = 1;
    $navigation = get_navigation($template, $active_id);

    /* Page content */
    $page_subtitle = 'Subtitle Home-page';
    $page_content = 'Content';

    /* Choose Template */
    include use_template('main');
}

/* Overview*/
elseif (new_route('/DDWT-Eindopdracht/rooms/overview/', 'get')) {
    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'Home' => na('/DDWT-Eindopdracht/rooms/', True),
        'Overview' => na('/DDWT-Eindopdracht/rooms/overview/', True)
    ]);
    $active_id = 2;
    $navigation = get_navigation($template, $active_id);

    /* Page content */
    $page_subtitle = 'Subtitle Overview';
    $page_content = 'Content Overview';

    /* Choose Template */
    include use_template('main');
}
