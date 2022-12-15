<?php
/*
 *  Made by Reeignn
 *  https://github.com/Verira/RadomeWEB
 *  RadomeWEB v2.1
 *
 *  License: GPL-3.0
 *
 *  UserCP overview
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_overview';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$user_details = [
    $language->get('user', 'username') => $user->getDisplayname(true),
    $language->get('admin', 'group') => Output::getClean($user->getMainGroup()->name),
    $language->get('admin', 'registered') => date(DATE_FORMAT, $user->data()->joined)
];

// Language values
$smarty->assign([
    'USER_CP' => $language->get('user', 'user_cp'),
    'USER_DETAILS' => $language->get('user', 'user_details'),
    'USER_DETAILS_VALUES' => $user_details,
    'OVERVIEW' => $language->get('user', 'overview')
]);

// Get graph data
$forum_enabled = Util::isModuleEnabled('Forum');

if ($forum_enabled) {
    $forum_query_user = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), COUNT(*) FROM rw_posts WHERE post_creator = ? AND created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", [$user->data()->id, strtotime('-7 days')])->results();
    $forum_query_average = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), (COUNT(*) / COUNT(Distinct post_creator)) FROM rw_posts WHERE created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", [strtotime('-7 days')])->results();
    $submissions_total = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), COUNT(*) FROM rw_forms_replies WHERE user_id = ? AND created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", [$user->data()->id, strtotime('-7 days')])->results();
    $store_orders = DB::getInstance()->query("SELECT FROM_UNIXTIME(created, '%Y-%m-%d'), COUNT(*) FROM rw_store_orders WHERE user_id = ? AND created > ? GROUP BY FROM_UNIXTIME(created, '%Y-%m-%d')", [$user->data()->id, strtotime('-7 days')])->results();

    $output = [];
    foreach ($forum_query_user as $item) {
        $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
        $output[$date]['user'] = $item->{'COUNT(*)'};
    }
    foreach ($forum_query_average as $item) {
        $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
        $output[$date]['average'] = $item->{'(COUNT(*) / COUNT(Distinct post_creator))'};
    }
    foreach ($forum_query_total as $item) {
        $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
        $output[$date]['total'] = $item->{'COUNT(*)'};
    }
    foreach ($submissions_total as $item) {
        $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
        $output[$date]['submissions'] = $item->{'COUNT(*)'};
    }
    foreach ($store_orders as $item) {
        $date = strtotime($item->{'FROM_UNIXTIME(created, \'%Y-%m-%d\')'});
        $output[$date]['store_orders'] = $item->{'COUNT(*)'};
    }
    

    // Fill in missing dates
    $graph_start = strtotime('-7 days');
    $graph_start = date('d M Y', $graph_start);
    $graph_start = strtotime($graph_start);
    $end = strtotime(date('d M Y'));
    while ($graph_start <= $end) {
        if (!isset($output[$graph_start]['user'])) {
            $output[$graph_start]['user'] = 0;
        }

        if (!isset($output[$graph_start]['average'])) {
            $output[$graph_start]['average'] = 0;
        }

        if (!isset($output[$graph_start]['total'])) {
            $output[$graph_start]['total'] = 0;
        }
        if (!isset($output[$graph_start]['submissions'])) {
            $output[$graph_start]['submissions'] = 0;
        }
        if (!isset($output[$graph_start]['store_orders'])) {
            $output[$graph_start]['store_orders'] = 0;
        }

        $graph_start += 86400;
    }

    ksort($output);

    // Turn into string for graph
    $labels = '';
    $user_data = '';
    $average_data = '';
    $submissions = '';
    $store_orders = '';
    foreach ($output as $date => $item) {
        $labels .= '"' . date('Y-m-d', $date) . '", ';
        $user_data .= $item['user'] . ', ';
        $average_data .= $item['average'] . ', ';
        $submissions .= $item['submissions'] . ', ';
        $store_orders .= $item['store_orders'] . ', ';
    }
    $labels = '[' . rtrim($labels, ', ') . ']';
    $user_data = '[' . rtrim($user_data, ', ') . ']';
    $average_data = '[' . rtrim($average_data, ', ') . ']';
    $submissions = '[' . rtrim($submissions, ', ') . ']';
    $store_orders = '[' . rtrim($store_orders, ', ') . ']';

    $smarty->assign('FORUM_GRAPH', $forum_language->get('forum', 'last_7_days_posts'));
}

if ($forum_enabled) {
    $template->assets()->include([
        AssetTree::MOMENT,
        AssetTree::CHART_JS,
    ]);

    $template->addJSScript(
        '
        $(document).ready(function() {
            var ctx = $("#dataChart").get(0).getContext("2d");

            moment.locale(\'' . (defined('HTML_LANG') ? strtolower(HTML_LANG) : 'en') . '\');

            var data = {
                labels: ' . $labels . ',
                datasets: [
                    {
                        label: "' . $forum_language->get('forum', 'your_posts') . '",
                        fill: false,
                        borderColor: "#ffde0a",
                        pointBorderColor: "#ffde0a",
                        pointBackgroundColor: "#fff",
                        tension: 0.1,
                        data: ' . $user_data . '
                    },
                    {
                        label: "' . $forum_language->get('forms', 'submissions') . '",
                        fill: false,
                        borderColor: "#ff7f00",
                        pointBorderColor: "#ff7f00",
                        pointBackgroundColor: "#fff",
                        tension: 0.1,
                        data: ' . $submissions . '
                    },
                    {
                        label: "' . $forum_language->get('store', 'store_orders') . '",
                        fill: false,
                        borderColor: "#4cf702",
                        pointBorderColor: "#4cf702",
                        pointBackgroundColor: "#fff",
                        tension: 0.1,
                        data: ' . $submissions . '
                    },
                ]
            }

            var dataLineChart = new Chart(ctx, {
                type: \'line\',
                data: data,
                options: {
                    scales: {
                        yAxes: [{
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                userCallback: function(label, index, labels) {
                                    // when the floored value is the same as the value we have a whole number
                                    if (Math.floor(label) === label) {
                                        return label;
                                    }

                                }
                            }
                        }],
                        xAxes: [{
                            type: \'time\',
                            time: {
                                unit: \'day\'
                            }
                        }]
                    }
                }
            });
        });
        '
    );
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

require(ROOT_PATH . '/core/templates/cc_navbar.php');

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('user/index.tpl', $smarty);
