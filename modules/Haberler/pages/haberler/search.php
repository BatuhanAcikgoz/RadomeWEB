<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Haberler search page
 */

if (!isset($haberler) || (!$haberler instanceof Haberler)) {
    $haberler = new Haberler();
}

const PAGE = 'haberler';

// Initialise
$timeago = new TimeAgo(TIMEZONE);

// Get user group ID
$user_groups = $user->getAllGroupIds();

if (!isset($_GET['s'])) {
    if (Input::exists()) {
        if (Token::check()) {
            $validation = Validate::check($_POST, [
                'haberler_search' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 3,
                    Validate::MAX => 128
                ]
            ]);

            if ($validation->passed()) {
                $search = str_replace(' ', '+', Output::getClean(Input::get('haberler_search')));
                $search = preg_replace('/[^a-zA-Z0-9 +]+/', '', $search); // alphanumeric only

                Redirect::to(URL::build('/haberler/search/', 's=' . urlencode($search) . '&p=1'));
            }

            $error = $haberler_language->get('haberler', 'invalid_search_query', ['min' => 3, 'max' => 128]);
        } else {
            $error = $language->get('general', 'invalid_token');
        }
    }
} else {
    $search = Output::getClean(str_replace('+', ' ', $_GET['s']));
    $search = preg_replace('/[^a-zA-Z0-9 +]+/', '', $search); // alphanumeric only

    if (isset($_GET['p']) && is_numeric($_GET['p'])) {
        $p = $_GET['p'];
    } else {
        $p = 1;
    }

    if (isset($_SESSION['last_haberler_search']) && $_SESSION['last_haberler_search_query'] != $_GET['s'] && $_SESSION['last_haberler_search'] > strtotime('-30 seconds')) {
        Session::flash('search_error', $haberler_language->get('haberler', 'search_again_in_x_seconds', ['count' => (30 - (date('U') - $_SESSION['last_haberler_search']))]));
        Redirect::to(URL::build('/haberler/search'));
    }

    $cache->setCache($search . '-' . rtrim(implode('-', $user_groups), '-'));
    if (!$cache->isCached('result')) {
        // Execute search
        $search_topics = DB::getInstance()->query('SELECT * FROM rw_haberlers WHERE haber_title LIKE ?', ['%' . $search . '%'])->results();
        $search_haberlers = DB::getInstance()->query('SELECT * FROM rw_haberlers WHERE post_content LIKE ?', ['%' . $search . '%'])->results();

        $search_results = array_merge($search_topics, $search_haberlers);

        $results = [];
        foreach ($search_results as $result) {
                        $post = DB::getInstance()->query('SELECT * FROM rw_haberlers WHERE id = ? ORDER BY post_date', [$result->id])->results();
                            $post = $post[0];
                            if (!isset($results[$post->id]) && $post->deleted == 0) {
                                $results[$post->id] = [
                                    'post_id' => $post->id,
                                    'topic_id' => $result->id,
                                    'topic_title' => $result->haber_title,
                                    'post_author' => $post->post_creator,
                                    'post_date' => $post->post_date,
                                    'post_content' => $post->post_content
                                ];

                                break;
                            }
        }

        $results = array_values($results);
        $cache->store('result', $results, 60);

        if (!isset($_SESSION['last_haberler_search_query']) || $_SESSION['last_haberler_search_query'] != $_GET['s']) {
            $_SESSION['last_haberler_search'] = date('U');
            $_SESSION['last_haberler_search_query'] = $_GET['s'];
        }
    } else {
        $results = $cache->retrieve('result');
    }

    $input = true;
}

if (!isset($_GET['s'])) {
    $page_title = $haberler_language->get('haberler', 'haberler_search');
} else {
    $page_title = $haberler_language->get('haberler', 'haberler_search') . ' - ' . Output::getClean(substr($search, 0, 20)) . ' - ' . $language->get('general', 'page_x', ['page' => $p]);
}
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->assets()->include([
    AssetTree::TINYMCE,
]);

if (isset($_GET['s'])) {
    // Show results
    if (count($results)) {
        $paginator = new Paginator(
            $template_pagination ?? null,
            $template_pagination_left ?? null,
            $template_pagination_right ?? null
        );
        $results = $paginator->getLimited($results, 10, $p, count($results));
        $pagination = $paginator->generate(7, URL::build('/haberler/search/', 's=' . urlencode($search) . '&'));

        $smarty->assign('PAGINATION', $pagination);

        // Posts to display on the page
        $haberlers = [];
        // Display the correct number of haberlers
        $n = 0;
        while (($n < count($results->data)) && isset($results->data[$n])) {
            // Purify post content
            $content = EventHandler::executeEvent('renderPost', ['content' => $results->data[$n]['post_content']])['content'];

            $post_user = new User($results->data[$n]['post_author']);
            $haberlers[$n] = [
                'post_author' => $post_user->getDisplayname(),
                'post_author_id' => Output::getClean($results->data[$n]['post_author']),
                'post_author_avatar' => $post_user->getAvatar(25),
                'post_author_profile' => $post_user->getProfileURL(),
                'post_author_style' => $post_user->getGroupStyle(),
                'post_date_full' => date(DATE_FORMAT, strtotime($results->data[$n]['post_date'])),
                'post_date_friendly' => $timeago->inWords($results->data[$n]['post_date'], $language),
                'content' => $content,
                'topic_title' => Output::getClean($results->data[$n]['haber_title']),
                'post_url' => URL::build('/haberler/haber/' . urlencode($results->data[$n]['id']) . '-' . $haberler->titleToURL($results->data[$n]['haber_title']), 'pid=' . $results->data[$n]['id'])
            ];
            $n++;
        }

        $results = null;

        $smarty->assign([
            'RESULTS' => $haberlers,
            'READ_FULL_POST' => $haberler_language->get('haberler', 'read_full_post')
        ]);
    } else {
        $smarty->assign('NO_RESULTS', $haberler_language->get('haberler', 'no_results_found'));
    }

    $smarty->assign([
        'SEARCH_RESULTS' => $haberler_language->get('haberler', 'search_results'),
        'NEW_SEARCH' => $haberler_language->get('haberler', 'new_search'),
        'NEW_SEARCH_URL' => URL::build('/haberler/search'),
        'SEARCH_TERM' => (isset($_GET['s']) ? Output::getClean($_GET['s']) : '')
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('haberler/search_results.tpl', $smarty);
} else {
    // Search bar
    if (isset($error)) {
        $smarty->assign('ERROR', $error);
    } else {
        if (Session::exists('search_error')) {
            $smarty->assign('ERROR', Session::flash('search_error'));
        }
    }

    $smarty->assign([
        'HABERLER_SEARCH' => $haberler_language->get('haberler', 'haberler_search'),
        'FORM_ACTION' => URL::build('/haberler/search'),
        'SEARCH' => $language->get('general', 'search'),
        'TOKEN' => Token::get(),
        'SUBMIT' => $language->get('general', 'submit'),
        'ERROR_TITLE' => $language->get('general', 'error')
    ]);

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('haberler/search.tpl', $smarty);
}
