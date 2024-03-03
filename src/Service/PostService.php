<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Post;
use DateTimeImmutable;

class PostService
{

    public function newPost(User $user) : Post {

        $post = new Post();
        $post->setAuthor($user);
        $post->setCreatedAt(new DateTimeImmutable());

        return $post;
    }
}
