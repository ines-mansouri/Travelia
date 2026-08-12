<?php

namespace App\Policies;

use App\Review;
use App\User;

class ReviewPolicy
{
    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }
}
