<?php
/**
 * Haberler class
 *
 * @package Modules\Haberler
 * @author Reeignn
 * @version 2.0.2
 * @license MIT
 */
class Haberler {

    private DB $_db;
    private static array $_permission_cache = [];
    private static array $_count_cache = [];

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
        $parent_haberlers = $this->_db->orderWhere('haberlers', 'deleted = 0', 'id', 'ASC')->results();

        $return = [];

        if (count($parent_haberlers)) {
            foreach ($parent_haberlers as $haberler) {
                if ($this->haberlerExist($haberler->id, $groups)) {
                    $return[$haberler->id]['description'] = Output::getClean($haberler->haberler_description);
                    $return[$haberler->id]['title'] = Output::getClean($haberler->haber_title);
                    $return[$haberler->id]['icon'] = Output::getPurified($haberler->icon);

                    // Get subhaberlers
                    $haberlers = $this->_db->orderWhere('haberlers', 'deleted = 0', 'id', 'ASC')->results();
                    if (count($haberlers)) {
                        foreach ($haberlers as $item) {
                            if ($this->haberlerExist($item->id, $groups)) {
                                $return[$haberler->id]['subhaberlers'][$item->id] = $item;
                                $return[$haberler->id]['subhaberlers'][$item->id]->haber_title = Output::getClean($item->haber_title);
                                $return[$haberler->id]['subhaberlers'][$item->id]->haberler_description = Output::getClean($item->haberler_description);
                                $return[$haberler->id]['subhaberlers'][$item->id]->icon = Output::getPurified($item->icon);
                                $return[$haberler->id]['subhaberlers'][$item->id]->link = URL::build('/haberler/goruntule/' . urlencode($item->id) . '-' . $this->titleToURL($item->haberler_title));
                                $return[$haberler->id]['subhaberlers'][$item->id]->redirect_to = Output::getClean($item->redirect_url);

                                // Get topic/post count

                                $haberlers = $this->_db->orderWhere('haberlers', 'id = ' . $item->id . ' AND deleted = 0', 'id', 'ASC')->results();
                                $haberlers = count($haberlers);
                                $return[$haberler->id]['subhaberlers'][$item->id]->haberlers = $haberlers;


                                // Get list of subhaberlers (names + links)
                                $subhaberlers = $this->_db->orderWhere('haberlers', 'deleted = 0', 'id', 'ASC')->results();
                                if (count($subhaberlers)) {
                                    foreach ($subhaberlers as $subhaberler) {
                                        if ($this->haberlerExist($subhaberler->id, $groups)) {
                                            if (!isset($return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers)) {
                                                $return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers = [];
                                            }
                                            $return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers[$subhaberler->id] = new stdClass();
                                            $return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers[$subhaberler->id]->title = Output::getClean($subhaberler->haberler_title);
                                            $return[$haberler->id]['subhaberlers'][$item->id]->subhaberlers[$subhaberler->id]->link = URL::build('/haberler/goruntule/' . urlencode($subhaberler->id) . '-' . $this->titleToURL($subhaberler->haberler_title));
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
     * @param int $id ID of the haberler
     * @param array $groups Array of groups the user is in
     * @return bool Whether the haberler exists or not
     */
    public function haberlerExist(int $id, array $groups = [0]): bool {
        $exists = $this->_db->get('haberlers', ['id', $id])->results();

        return false;
    }



    public function titleToURL(string $topic = null): string {
        return URL::urlSafe($topic ?? '');
    }

    // Returns true/false depending on whether the current user can view a haberler
    // Params: $id (integer) - haberler id to check, $groups (array) - user groups
    public function canViewOtherTopics(int $id, array $groups = [0]): bool {
        $cache_key = 'topics_view_' . $id . '_' . implode('_', $groups);
        if (isset(self::$_permission_cache[$cache_key])) {
            return true;
        }
        // Does the haberler exist?
        $exists = $this->_db->get('haberlers', ['id', $id])->results();
        if (count($exists)) {
            // Can the user view other topics?
            $access = $this->_db->get('haberlers_permissions', ['id', $id])->results();

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

    /**
     * Determine if a topic exists or not.
     *
     * @param int $topic_id The topic ID
     * @return bool Whether the topic exists or not
     */
    public function topicExist(int $topic_id): bool {
        // Does the topic exist?
        $exists = $this->_db->get('haberlers', ['id', $topic_id])->results();
        return count($exists) > 0;
    }

    /**
     * Update the database with the new latest haberler haberlers.
     */
    public function updateHaberlerLatestPosts(): void {
        $haberlers = $this->_db->get('haberlers', ['id', '<>', 0])->results();
        $latest_haberlers = [];
        $n = 0;

        foreach ($haberlers as $item) {
            if ($item->parent != 0) {
                $latest_post_query = $this->_db->orderWhere('haberlers', 'id = ' . $item->id, 'post_date', 'DESC')->results();

                if (!empty($latest_post_query)) {
                    foreach ($latest_post_query as $latest_post) {
                        if ($latest_post->deleted != 1) {
                            // Ensure topic isn't deleted
                            $topic_query = $this->_db->get('haberlers', ['id', $latest_post->topic_id])->results();

                            if (empty($topic_query)) {
                                continue;
                            }

                            $latest_haberlers[$n]['id'] = $item->id;
                            if ($latest_post->created) {
                                $latest_haberlers[$n]['date'] = $latest_post->created;
                            } else {
                                $latest_haberlers[$n]['date'] = strtotime($latest_post->post_date);
                            }
                            $latest_haberlers[$n]['author'] = $latest_post->post_creator;
                            $latest_haberlers[$n]['topic_id'] = $latest_post->topic_id;

                            break;
                        }
                    }
                }

                if (!isset($latest_haberlers[$n])) {
                    $latest_haberlers[$n]['id'] = $item->id;
                    $latest_haberlers[$n]['date'] = null;
                    $latest_haberlers[$n]['author'] = null;
                    $latest_haberlers[$n]['topic_id'] = null;
                }

                $n++;
            }
        }

        $haberlers = null;

        if (count($latest_haberlers)) {
            foreach ($latest_haberlers as $latest_post) {
                $this->_db->update('haberlers', $latest_post['id'], [
                    'last_post_date' => $latest_post['date'],
                    'last_user_posted' => $latest_post['author'],
                    'last_topic_posted' => $latest_post['topic_id']
                ]);
            }
        }

        $latest_haberlers = null;
    }

    /**
     * Update the database with the new latest haberler topic haberlers.
     */
    public function updateTopicLatestPosts(): void {
        $topics = $this->_db->get('haberlers', ['id', '<>', 0])->results();
        $latest_haberlers = [];
        $n = 0;

        foreach ($topics as $topic) {
            $latest_post_query = $this->_db->orderWhere('haberlers', 'topic_id = ' . $topic->id, 'post_date', 'DESC')->results();

            if (count($latest_post_query)) {
                foreach ($latest_post_query as $latest_post) {
                    if ($latest_post->deleted != 1) {
                        $latest_haberlers[$n]['topic_id'] = $topic->id;

                        if ($latest_post->created != null) {
                            $latest_haberlers[$n]['date'] = $latest_post->created;
                        } else {
                            $latest_haberlers[$n]['date'] = strtotime($latest_post->post_date);
                        }

                        $latest_haberlers[$n]['author'] = $latest_post->post_creator;

                        break;
                    }
                }
            }

            $n++;
        }

        foreach ($latest_haberlers as $latest_post) {
            if (!empty($latest_post['date'])) {
                $this->_db->update('haberlers', $latest_post['topic_id'], [
                    'topic_reply_date' => $latest_post['date'],
                    'topic_last_user' => $latest_post['author']
                ]);
            }
        }
    }

    /**
     * Get the title of a specific haberler.
     *
     * @param int $id The haberler ID to get the title of.
     * @return string The haberler title.
     */
    public function getHaberlerTitle(int $id): string {
        $data = $this->_db->get('haberlers', ['id', $id])->results();
        return $data[0]->haberler_title;
    }

    /**
     * Get data of a specific post.
     *
     * @param int $post_id The post ID to data about.
     * @return array|false The post data or false on failure.
     */
    public function getIndividualPost(int $post_id) {
        $data = $this->_db->get('haberlers', ['id', $post_id])->results();
        if (count($data)) {
            return [
                'creator' => $data[0]->post_creator,
                'content' => $data[0]->post_content,
                'date' => $data[0]->post_date,
                'id' => $data[0]->id,
                'topic_id' => $data[0]->topic_id
            ];
        }
        return false;
    }

    /**
     * Get the latest news haberlers to display on homepage.
     *
     * @param int $number The number of haberlers to get.
     * @return array The latest news haberlers.
     */
    public function getLatestNews(int $number = 5): array {
        $return = []; // Array to return containing news

        $news_items = $this->_db->query('SELECT * FROM rw_haberlers WHERE id AND deleted = 0 ORDER BY post_date DESC LIMIT 10')->results();

        foreach ($news_items as $item) {
            $news_post = $this->_db->get('haberlers', ['id', $item->id])->results();

            if (is_null($news_post[0]->created)) {
                $post_date = date(DATE_FORMAT, strtotime($news_post[0]->post_date));
            } else {
                $post_date = date(DATE_FORMAT, $news_post[0]->created);
            }

            $post = $news_post[0]->post_content;
            $return[] = [
                'id' => $item->id,
                'post_date' => $post_date,
                'haber_title' => $item->haber_title,
                'post_views' => $item->post_views,
                'author' => $item->post_creator,
                'content' => Text::truncate($post),
                'created' => $item->created,
            ];
        }

        // Order the discussions by date - most recent first
        usort($return, static function ($a, $b) {
            return strtotime($b['post_date']) - strtotime($a['post_date']);
        });

        return array_slice($return, 0, $number, true);
    }

    public function getHaberView(int $number = 5): array {
        $return = []; // Array to return containing news
        $labels_cache = []; // Array to contain labels

        $news_items = $this->_db->query('SELECT * FROM rw_haberlers WHERE id AND deleted = 0 ORDER BY post_date')->results();

        foreach ($news_items as $item) {
            $news_post = $this->_db->get('haberlers', ['id', $item->id])->results();

            if (is_null($news_post[0]->created)) {
                $post_date = date(DATE_FORMAT, strtotime($news_post[0]->post_date));
            } else {
                $post_date = date(DATE_FORMAT, $news_post[0]->created);
            }

            $post = $news_post[0]->post_content;
            $return[] = [
                'id' => $item->id,
                'post_date' => $post_date,
                'haber_title' => $item->haber_title,
                'post_views' => $item->post_views,
                'author' => $item->post_creator,
                'content' => Text::truncate($post),
                'created' => $item->created,
            ];
        }

        // Order the discussions by date - most recent first
        usort($return, static function ($a, $b) {
            return strtotime($b['post_date']) - strtotime($a['post_date']);
        });

        return array_slice($return, 0, $number, true);
    }


    /**
     * Determine if groups have permission to moderate a haberler.
     *
     * @param int|null $id The haberler ID to check.
     * @param array $groups The groups to check.
     * @return bool Whether the groups can moderate the haberler.
     */
    public function canModerateHaberler(int $id = null, array $groups = [0]): bool {
        if (!$id || in_array(0, $groups)) {
            return false;
        }

        $cache_key = 'moderate_' . $id . '_' . implode('_', $groups);
        if (isset(self::$_permission_cache[$cache_key])) {
            return true;
        }

        $permissions = $this->_db->get('haberlers_permissions', ['id', $id])->results();

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
     * @return int Number of haberlers
     */
    public function getPostCount(int $user_id = null): int {
        if ($user_id) {
            if (isset(self::$_count_cache["haberlers_$user_id"])) {
                return self::$_count_cache["haberlers_$user_id"];
            }
            $count = $this->_db->query('SELECT COUNT(*) AS c FROM rw_haberlers WHERE deleted = 0 AND post_creator = ?', [$user_id])->first()->c;
            self::$_count_cache["haberlers_$user_id"] = $count;
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
            $count = $this->_db->query('SELECT COUNT(*) AS c FROM rw_topics WHERE deleted = 0 AND topic_creator = ?', [$user_id])->first()->c;
            self::$_count_cache["topics_$user_id"] = $count;
            return $count;
        }

        return 0;
    }

    /**
     * Get haberlers on a specific topic.
     *
     * @param int|null $tid The topic ID to check.
     * @return array|false Array of topics or false on failure.
     */
    public function getPosts(int $tid = null) {
        if ($tid) {
            // Get haberlers from database
            $haberlers = $this->_db->get('haberlers', ['id', $tid]);

            if ($haberlers->count()) {
                $haberlers = $haberlers->results();

                // Remove deleted haberlers
                foreach ($haberlers as $key => $post) {
                    if ($post->deleted == 1) {
                        unset($haberlers[$key]);
                    }
                }

                return array_values($haberlers);
            }
        }
        return false;
    }

    /**
     * Get any subhaberlers at any level for a haberler
     *
     * @param int $id The haberler ID
     * @param array $groups The user groups
     * @param int $depth The depth of the subhaberlers to get
     * @return array Subhaberlers at any level for a haberler
     */
    public function getAnySubhaberlers(int $id, array $groups = [0], int $depth = 0): array {
        if ($depth == 10) {
            return [];
        }

        $ret = [];

        $subhaberlers_query = $this->_db->query('SELECT * FROM rw_haberlers WHERE deleted = ? ORDER BY id ASC', [$id]);

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
