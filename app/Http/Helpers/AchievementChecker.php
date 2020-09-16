<?php

use App\User;
use App\Achievement;


class AchievementChecker {
        function checkAndUpdateForUser(User $user) {
            $userAchievements = Achievement::where('required_points', '<=', $user->points)->get();
            $user->achievements()->sync($userAchievements);
        }
    }

?>
