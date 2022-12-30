<?php
/**
 * Haberler class
 *
 * @package Modules\Haberler
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class Haberler {

    private DB $_db;
    private static array $_permission_cache = [];
    private static array $_count_cache = [];
    private const URL_EXCLUDE_CHARS = [
        '?',
        '&',
        '/',
        '#',
        '.',
    ];

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /**
     * Get an array of haberlers a user can access, including topic information
     *
     * @param array $groups Users groups
     * @param int $user_id User ID
     * @return array Array of haberlers a user can access
     */
    public function listAllHaberlers(array $groups = [0], int $user_id = 0): array {
        if (in_array(0, $groups)) {
            $user_id = 0;
        }

        if (!$user_id) {
            $user_id = 0;
        }

        // Get a list of parent haberlers
        $parent_haberlers = $this->_db->orderWhere('haberlers', 'parent = 0', 'haberler_order', 'ASC')->results();

        $return = [];

        if (count($parent_haberlers)) {
            foreach ($parent_haberlers as $haberler) {
                if ($this->haberlerExist($haberler->id, $groups)) {
                    $return[$haberler->id]['description'] = Output::getClean($haberler->haberler_description);
                    $return[$haberler->id]['title'] = Output::getClean($haberler->haberler_title);
                    $return[$haberler->id]['icon'] = Output::getPurified($haberler->icon);

                    // Get subhaberlers
                    $haberlers = $this->_db->orderWhere('haberlers', 'parent = ' . $haberler->id, 'haberler_order', 'ASC')->results();
                    if (count($haberlers)) {
                        foreach ($haberlers as $item) {
                            if ($this->haberlerExist($item->id, $groups)) {
                                $return[$haberler->id]['subhaberlers'][$item->id] = $item;
                                $return[$haberler->id]['subhaberlers'][$item->id]->haberler_title = Output::getClean($item->haberler_title);
                                $return[$haberler->id]['subhaberlers'][$item->id]->haberler_description = Output::getClean($item->haberler_description);
                                $return[$haberler->id]['subhaberlers'][$item->id]->icon = Output::getPurified($item->icon);
                                $return[$haberler->id]['subhaberlers'][$item->id]->link = URL::build('/haberler/view/' . urlencode($item->id) . '-' . $this->titleToURL($item->haberler_title));
                                $return[$haberler->id]['subhaberlers'][$item->id]->redirect_to = Output::getClean($item->redirect_url);

                                // Get topic/post count
                                $topics = $this->_db->orderWhere('topics', 'haberler_id = ' . $item->id . ' AND deleted = 0', 'id', 'ASC')->results();
                                $topics = count($topics);
                                $return[$haberler->id]['subhaberlers'][$item->id]->topics = $topics;

                                $posts = $this->_db->orderWhere('posts', 'haberler_id = ' . $item->id . ' AND deleted = 0', 'id', 'ASC')->results();
                                $posts = count($posts);
                                $return[$haberler->id]['subhaberlers'][$item->id]->posts = $posts;

                                // Can the user view other topics
                                if ($item->last_user_posted == $user_id || $this->canViewOtherTopics($item->id, $groups)) {
                                    if ($item->last_topic_posted) {
                                        // Last reply
                                        $last_reply = $this->_db->orderWhere('posts', 'topic_id = ' . $item->last_topic_posted, 'created', 'DESC')->results();
                                    } else {
                                        $last_reply = null;
                                    }
                                } else {
                                    $last_topic = $this->_db->orderWhere('topics', 'haberler_id = ' . $item->id . ' AND deleted = 0 AND topic_creator = ' . $user_id, 'topic_reply_date', 'DESC')->results();
                                    if (count($last_topic)) {
                                        $last_reply = $this->_db->orderWhere('posts', 'topic_id = ' . $last_topic[0]->id, 'created', 'DESC')->results();
                                    } else {
                                        $last_reply = null;
                                    }
                                }

                                if (isset($last_reply) && count($last_reply)) {
                                    $n = 0;
                                    while (isset($last_reply[$n]) && $last_reply[$n]->deleted == 1) {
                                        $n++;
                                    }

                                    if (!isset($last_reply[$n])) {
                                        continue;
                                    }

                                    // Title
                                    $last_topic = $this->_db->get('topics', ['id', $last_reply[$n]->topic_id])->results();

                                    $return[$haberler->id]['subhaberlers'][$item->id]->last_post = $last_reply[$n];
                                    $return[$haberler->id]['subhaberlers'][$item->id]->last_post->title = Output::getClean($last_topic[0]->topic_title);
                                    $return[$haberler->id]['subhaberlers'][$item->id]->last_post->link = URL::build('/haberler/topic/' . urlencode($last_reply[$n]->topic_id) . '-' . $this->titleToURL($last_topic[0]->topic_title), 'pid=' . $last_reply[0]->id);
                                }

                                // Get list of subhaberlers (names + links)
                                $subhaberlers = $this->_db->orderWhere('haberlers', 'parent = ' . $item->id, 'haberler_order', 'ASC')->results();
                                if (count($subhaberlers)) {
                                    foreach ($subhaberlers as $subhaberler) {
                                        if ($this->haberlerExist($subhaberler->id, $groups)) {
                                            if (!isset($return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers)) {
                                                $return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers = [];
                                            }
                                            $return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers[$subhaberler->id] = new stdClass();
                                            $return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers[$subhaberler->id]->title = Output::getClean($subhaberler->haberler_title);
                                            $return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers[$subhaberler->id]->link = URL::build('/haberler/view/' . urlencode($subhaberler->id) . '-' . $this->titleToURL($subhaberler->haberler_title));
                                            $return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers[$subhaberler->id]->icon = Output::getPurified($subhaberler->icon);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $return;
    }

    /**
     * Determine if a haberler exists (in the context of a specific user)
     *
     * @param int $haberler_id ID of the haberler
     * @param array $groups Array of groups the user is in
     * @return bool Whether the haberler exists or not
     */
    public function haberlerExist(int $haberler_id, array $groups = [0]): bool {
        $exists = $this->_db->get('haberlers', ['id', $haberler_id])->results();
        if (count($exists)) {
            return $this->hasPermission($haberler_id, 'view', $groups);
        }

        return false;
    }

    /**
     * Determines if any groups have permission to do a certain action on a haberler
     *
     * @param int $haberler_id ID of the haberler
     * @param string $required_permission Required permission
     * @param array $groups Array of groups the user is in
     * @return bool Whether the groups have permission or not
     */
    private function hasPermission(int $haberler_id, string $required_permission, array $groups): bool {
        $cache_key = 'haberler_permissions_' . $haberler_id . '_' . $required_permission . '_' . implode('_', $groups);
        if (isset(self::$_permission_cache[$cache_key])) {
            return true;
        }
        $permissions = $this->_db->get('haberlers_permissions', ['haberler_id', $haberler_id])->results();
        foreach ($permissions as $permission) {
            if (in_array($permission->group_id, $groups)) {
                if ($permission->{$required_permission} == 1) {
                    self::$_permission_cache[$cache_key] = true;
                    return true;
                }
            }
        }
        return false;
    }

    public function titleToURL(string $topic = null): string {
        if ($topic) {
            $topic = str_replace(self::URL_EXCLUDE_CHARS, '', Util::cyrillicToLatin($topic));
            return Output::getClean(strtolower(urlencode(str_replace(' ', '-', $topic))));
        }

        return '';
    }

    // Returns true/false depending on whether the current user can view a haberler
    // Params: $haberler_id (integer) - haberler id to check, $groups (array) - user groups
    public function canViewOtherTopics(int $haberler_id, array $groups = [0]): bool {
        $cache_key = 'topics_view_' . $haberler_id . '_' . implode('_', $groups);
        if (isset(self::$_permission_cache[$cache_key])) {
            return true;
        }
        // Does the haberler exist?
        $exists = $this->_db->get('haberlers', ['id', $haberler_id])->results();
        if (count($exists)) {
            // Can the user view other topics?
            $access = $this->_db->get('haberlers_permissions', ['haberler_id', $haberler_id])->results();

            foreach ($access as $item) {
                if (in_array($item->group_id, $groups)) {
                    if ($item->view_other_topics == 1) {
                        self::$_permission_cache[$cache_key] = true;
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Get the newest 50 topics this user/group can view
     *
     * @param array $groups Array of groups the user is in
     * @param int $user_id User ID
     * @return array 50 latest topics
     */
    public function getLatestDiscussions(array $groups = [0], int $user_id = 0): array {
        if (!$user_id) {
            $user_id = 0;
        }

        $all_topics_haberlers = DB::getInstance()->query('SELECT haberler_id FROM nl2_haberlers_permissions WHERE group_id IN (' . rtrim(implode(',', $groups), ',') . ') AND `view` = 1 AND view_other_topics = 1')->results();

        $own_topics_haberlers = [];
        if ($user_id > 0) {
            $own_topics_haberlers = DB::getInstance()->query('SELECT haberler_id FROM nl2_haberlers_permissions WHERE group_id IN (' . rtrim(implode(',', $groups), ',') . ') AND `view` = 1 AND view_other_topics = 0')->results();
        }

        if (!count($all_topics_haberlers) && !count($own_topics_haberlers)) {
            return [];
        }

        foreach ($all_topics_haberlers as $haberler) {
            $all_topics_haberlers_string .= $haberler->haberler_id . ',';
        }
        $all_topics_haberlers_string = rtrim($all_topics_haberlers_string, ',');

        if (count($own_topics_haberlers)) {
            foreach ($own_topics_haberlers as $haberler) {
                $own_topics_haberlers_string .= $haberler->haberler_id . ',';
            }
            $own_topics_haberlers_string = rtrim($own_topics_haberlers_string, ',');

            return DB::getInstance()->query(
                '(SELECT topics.id as id, topics.haberler_id as haberler_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND topics.haberler_id IN (' . $all_topics_haberlers_string . ') ORDER BY topics.topic_reply_date DESC LIMIT 50)
                UNION
                (SELECT topics.id as id, topics.haberler_id as haberler_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id FROM nl2_topics topics LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) WHERE topics.deleted = 0 AND ((topics.haberler_id IN (' . $own_topics_haberlers_string . ') AND topics.topic_creator = ?) OR topics.sticky = 1) ORDER BY topics.topic_reply_date DESC LIMIT 50)
                ORDER BY topic_reply_date DESC LIMIT 50',
                [$user_id],
                true
            )->results();
        }

        return DB::getInstance()->query(
            'SELECT topics.id as id, topics.haberler_id as haberler_id, topics.topic_title as topic_title, topics.topic_creator as topic_creator, topics.topic_last_user as topic_last_user, topics.topic_date as topic_date, topics.topic_reply_date as topic_reply_date, topics.topic_views as topic_views, topics.locked as locked, topics.sticky as sticky, topics.label as label, topics.deleted as deleted, posts.id as last_post_id
            FROM nl2_topics topics 
            LEFT JOIN nl2_posts posts ON topics.id = posts.topic_id AND posts.id = (SELECT MAX(id) FROM nl2_posts p WHERE p.topic_id = topics.id AND p.deleted = 0) 
            WHERE topics.deleted = 0 AND topics.haberler_id IN (' . $all_topics_haberlers_string . ') ORDER BY topics.topic_reply_date DESC LIMIT 50',
        )->results();
    }

    /**
     * Determine if a topic exists or not.
     *
     * @param int $topic_id The topic ID
     * @return bool Whether the topic exists or not
     */
    public function topicExist(int $topic_id): bool {
        // Does the topic exist?
        $exists = $this->_db->get('topics', ['id', $topic_id])->results();
        return count($exists) > 0;
    }

    /**
     * Determine if the groups can view the haberler or not.
     *
     * @param int $haberler_id The haberler ID
     * @param array $groups The user's groups
     * @return bool Whether the groups can view the haberler or not
     */
    public function canViewHaberler(int $haberler_id, array $groups = [0]): bool {
        return $this->hasPermission($haberler_id, 'view', $groups);
    }

    /**
     * Determine if the groups can post topics in the haberler or not.
     *
     * @param int $haberler_id The haberler ID
     * @param array $groups The user's groups
     * @return bool Whether the groups can post topics in the haberler or not
     */
    public function canPostTopic(int $haberler_id, array $groups = [0]): bool {
        return $this->hasPermission($haberler_id, 'create_topic', $groups);
    }

    /**
     * Determine if the groups can post replies in the haberler or not.
     *
     * @param int $haberler_id The haberler ID
     * @param array $groups The user's groups
     * @return bool Whether the groups can post replies in the haberler or not
     */
    public function canPostReply(int $haberler_id, array $groups = [0]): bool {
        return $this->hasPermission($haberler_id, 'create_post', $groups);
    }

    /**
     * Determine if the groups can edit [psts] in the haberler or not.
     *
     * @param int $haberler_id The haberler ID
     * @param array $groups The user's groups
     * @return bool Whether the groups can edit posts in the haberler or not
     */
    public function canEditTopic(int $haberler_id, array $groups = [0]): bool {
        return $this->hasPermission($haberler_id, 'edit_topic', $groups);
    }

    /**
     * Update the database with the new latest haberler posts.
     */
    public function updateHaberlerLatestPosts(): void {
        $haberlers = $this->_db->get('haberlers', ['id', '<>', 0])->results();
        $latest_posts = [];
        $n = 0;

        foreach ($haberlers as $item) {
            if ($item->parent != 0) {
                $latest_post_query = $this->_db->orderWhere('posts', 'haberler_id = ' . $item->id, 'post_date', 'DESC')->results();

                if (!empty($latest_post_query)) {
                    foreach ($latest_post_query as $latest_post) {
                        if ($latest_post->deleted != 1) {
                            // Ensure topic isn't deleted
                            $topic_query = $this->_db->get('topics', ['id', $latest_post->topic_id])->results();

                            if (empty($topic_query)) {
                                continue;
                            }

                            $latest_posts[$n]['haberler_id'] = $item->id;
                            if ($latest_post->created) {
                                $latest_posts[$n]['date'] = $latest_post->created;
                            } else {
                                $latest_posts[$n]['date'] = strtotime($latest_post->post_date);
                            }
                            $latest_posts[$n]['author'] = $latest_post->post_creator;
                            $latest_posts[$n]['topic_id'] = $latest_post->topic_id;

                            break;
                        }
                    }
                }

                if (!isset($latest_posts[$n])) {
                    $latest_posts[$n]['haberler_id'] = $item->id;
                    $latest_posts[$n]['date'] = null;
                    $latest_posts[$n]['author'] = null;
                    $latest_posts[$n]['topic_id'] = null;
                }

                $n++;
            }
        }

        $haberlers = null;

        if (count($latest_posts)) {
            foreach ($latest_posts as $latest_post) {
                $this->_db->update('haberlers', $latest_post['haberler_id'], [
                    'last_post_date' => $latest_post['date'],
                    'last_user_posted' => $latest_post['author'],
                    'last_topic_posted' => $latest_post['topic_id']
                ]);
            }
        }

        $latest_posts = null;
    }

    /**
     * Update the database with the new latest haberler topic posts.
     */
    public function updateTopicLatestPosts(): void {
        $topics = $this->_db->get('topics', ['id', '<>', 0])->results();
        $latest_posts = [];
        $n = 0;

        foreach ($topics as $topic) {
            $latest_post_query = $this->_db->orderWhere('posts', 'topic_id = ' . $topic->id, 'post_date', 'DESC')->results();

            if (count($latest_post_query)) {
                foreach ($latest_post_query as $latest_post) {
                    if ($latest_post->deleted != 1) {
                        $latest_posts[$n]['topic_id'] = $topic->id;

                        if ($latest_post->created != null) {
                            $latest_posts[$n]['date'] = $latest_post->created;
                        } else {
                            $latest_posts[$n]['date'] = strtotime($latest_post->post_date);
                        }

                        $latest_posts[$n]['author'] = $latest_post->post_creator;

                        break;
                    }
                }
            }

            $n++;
        }

        foreach ($latest_posts as $latest_post) {
            if (!empty($latest_post['date'])) {
                $this->_db->update('topics', $latest_post['topic_id'], [
                    'topic_reply_date' => $latest_post['date'],
                    'topic_last_user' => $latest_post['author']
                ]);
            }
        }
    }

    /**
     * Get the title of a specific haberler.
     *
     * @param int $haberler_id The haberler ID to get the title of.
     * @return string The haberler title.
     */
    public function getHaberlerTitle(int $haberler_id): string {
        $data = $this->_db->get('haberlers', ['id', $haberler_id])->results();
        return $data[0]->haberler_title;
    }

    /**
     * Get data of a specific post.
     *
     * @param int $post_id The post ID to data about.
     * @return array|false The post data or false on failure.
     */
    public function getIndividualPost(int $post_id) {
        $data = $this->_db->get('posts', ['id', $post_id])->results();
        if (count($data)) {
            return [
                'creator' => $data[0]->post_creator,
                'content' => $data[0]->post_content,
                'date' => $data[0]->post_date,
                'haberler_id' => $data[0]->haberler_id,
                'topic_id' => $data[0]->topic_id
            ];
        }
        return false;
    }

    /**
     * Get the latest news posts to display on homepage.
     *
     * @param int $number The number of posts to get.
     * @return array The latest news posts.
     */
    public function getLatestNews(int $number = 5): array {
        $return = []; // Array to return containing news
        $labels_cache = []; // Array to contain labels

        $news_items = $this->_db->query('SELECT * FROM nl2_topics WHERE haberler_id IN (SELECT id FROM nl2_haberlers WHERE news = 1) AND deleted = 0 ORDER BY topic_date DESC LIMIT 10')->results();

        foreach ($news_items as $item) {
            $news_post = $this->_db->get('posts', ['topic_id', $item->id])->results();
            $posts = count($news_post);

            if (is_null($news_post[0]->created)) {
                $post_date = date(DATE_FORMAT, strtotime($news_post[0]->post_date));
            } else {
                $post_date = date(DATE_FORMAT, $news_post[0]->created);
            }

            $labels = [];

            if ($item->labels) {
                // Get label
                $label_ids = explode(',', $item->labels);

                if ($label_ids !== false) {
                    foreach ($label_ids as $label_id) {
                        if (isset($labels_cache[$label_id])) {
                            $labels[] = $labels_cache[$label_id];
                        } else {
                            $label = $this->_db->get('haberlers_topic_labels', ['id', $label_id]);
                            if ($label->count()) {
                                $label = $label->first();

                                $label_html = $this->_db->get('haberlers_labels', ['id', $label->label]);

                                if ($label_html->count()) {
                                    $label_html = $label_html->first()->html;
                                    $label = str_replace('{x}', Output::getClean($label->name), Output::getPurified($label_html));
                                } else {
                                    $label = '';
                                }
                            } else {
                                $label = '';
                            }

                            $labels_cache[$label_id] = $label;
                            $labels[] = $label;
                        }
                    }
                }
            }

            $post = $news_post[0]->post_content;
            $return[] = [
                'topic_id' => $item->id,
                'topic_date' => $post_date,
                'topic_title' => $item->topic_title,
                'topic_views' => $item->topic_views,
                'author' => $item->topic_creator,
                'content' => Text::truncate($post),
                'replies' => $posts,
                'label' => count($labels) ? $labels[0] : null,
                'labels' => $labels
            ];
        }

        // Order the discussions by date - most recent first
        usort($return, static function ($a, $b) {
            return strtotime($b['topic_date']) - strtotime($a['topic_date']);
        });

        return array_slice($return, 0, $number, true);
    }

    /**
     * Determine if groups have permission to moderate a haberler.
     *
     * @param int|null $haberler_id The haberler ID to check.
     * @param array $groups The groups to check.
     * @return bool Whether the groups can moderate the haberler.
     */
    public function canModerateHaberler(int $haberler_id = null, array $groups = [0]): bool {
        if (!$haberler_id || in_array(0, $groups)) {
            return false;
        }

        $cache_key = 'moderate_' . $haberler_id . '_' . implode('_', $groups);
        if (isset(self::$_permission_cache[$cache_key])) {
            return true;
        }

        $permissions = $this->_db->get('haberlers_permissions', ['haberler_id', $haberler_id])->results();

        // Check the haberler
        foreach ($permissions as $permission) {
            if (in_array($permission->group_id, $groups)) {
                if ($permission->moderate == 1) {
                    self::$_permission_cache[$cache_key] = true;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get a user's post count
     *
     * @param int|null $user_id User ID to check
     * @return int Number of posts
     */
    public function getPostCount(int $user_id = null): int {
        if ($user_id) {
            if (isset(self::$_count_cache["posts_$user_id"])) {
                return self::$_count_cache["posts_$user_id"];
            }
            $count = $this->_db->query('SELECT COUNT(*) AS c FROM nl2_posts WHERE deleted = 0 AND post_creator = ?', [$user_id])->first()->c;
            self::$_count_cache["posts_$user_id"] = $count;
            return $count;
        }

        return 0;
    }

    /**
     * Get a user's topic count
     *
     * @param int|null $user_id User ID to check
     * @return int Number of topics
     */
    public function getTopicCount(int $user_id = null): int {
        if ($user_id) {
            if (isset(self::$_count_cache["topics_$user_id"])) {
                return self::$_count_cache["topics_$user_id"];
            }
            $count = $this->_db->query('SELECT COUNT(*) AS c FROM nl2_topics WHERE deleted = 0 AND topic_creator = ?', [$user_id])->first()->c;
            self::$_count_cache["topics_$user_id"] = $count;
            return $count;
        }

        return 0;
    }

    /**
     * Get posts on a specific topic.
     *
     * @param int|null $tid The topic ID to check.
     * @return array|false Array of topics or false on failure.
     */
    public function getPosts(int $tid = null) {
        if ($tid) {
            // Get posts from database
            $posts = $this->_db->get('posts', ['topic_id', $tid]);

            if ($posts->count()) {
                $posts = $posts->results();

                // Remove deleted posts
                foreach ($posts as $key => $post) {
                    if ($post->deleted == 1) {
                        unset($posts[$key]);
                    }
                }

                return array_values($posts);
            }
        }
        return false;
    }

    /**
     * Get any subhaberlers at any level for a haberler
     *
     * @param int $haberler_id The haberler ID
     * @param array $groups The user groups
     * @param int $depth The depth of the subhaberlers to get
     * @return array Subhaberlers at any level for a haberler
     */
    public function getAnySubhaberlers(int $haberler_id, array $groups = [0], int $depth = 0): array {
        if ($depth == 10) {
            return [];
        }

        $ret = [];

        $subhaberlers_query = $this->_db->query('SELECT * FROM nl2_haberlers WHERE parent = ? ORDER BY haberler_order ASC', [$haberler_id]);

        if (!$subhaberlers_query->count()) {
            return $ret;
        }

        foreach ($subhaberlers_query->results() as $result) {
            if ($this->haberlerExist($result->id, $groups)) {
                $to_add = new stdClass();
                $to_add->id = Output::getClean($result->id);
                $to_add->haberler_title = Output::getClean($result->haberler_title);
                $to_add->category = false;
                $ret[] = $to_add;

                $subhaberlers = $this->getAnySubhaberlers($result->id, $groups, ++$depth);

                if (count($subhaberlers)) {
                    foreach ($subhaberlers as $subhaberler) {
                        $ret[] = $subhaberler;
                    }
                }
            }
        }

        return $ret;
    }
}
