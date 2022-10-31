<?php

class UserProfilePostSeeder extends Seeder {

    public array $tables = [
        'rw_user_profile_wall_posts',
        'rw_user_profile_wall_posts_replies',
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $users = $db->get('users', ['id', '<>', 0])->results();

        $this->times(500, function () use ($db, $faker, $users) {
            $user = $faker->randomElement($users);
            $author = $faker->randomElement($users);
            while ($user->id == $author->id) {
                $author = $faker->randomElement($users);
            }

            $db->insert('user_profile_wall_posts', [
                'user_id' => $user->id,
                'author_id' => $author->id,
                'time' => $this->since($author->joined, $faker)->format('U'),
                'content' => $faker->text,
            ]);
        });

        $profile_posts = $db->get('user_profile_wall_posts', ['id', '<>', 0])->results();
        $this->times(500, function () use ($db, $faker, $profile_posts) {
            $post = $faker->randomElement($profile_posts);
            $author_id = $faker->randomElement($profile_posts)->author_id;

            $db->insert('user_profile_wall_posts_replies', [
                'post_id' => $post->id,
                'author_id' => $author_id,
                'time' => $this->since($post->time, $faker)->format('U'),
                'content' => $faker->text,
            ]);
        });
    }
}
