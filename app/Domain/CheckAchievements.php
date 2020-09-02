<?php

use App\Achievement;
use App\User;

class AchievementChecker {
        function checkAndUpdateForUser(User $user) {
            $userAchievements = Achievement::where('reward', '<=', $user->points)->get();
            $user->achievements()->sync($userAchievements);
        }
    }

?>
