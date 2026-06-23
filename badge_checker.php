<?php

function give_badge($conn, $user_id, $badge_name, $badge_icon) {
    $check = $conn->prepare("SELECT badge_name FROM user_badges WHERE user_id=? AND badge_name=?");
    $check->bind_param("is", $user_id, $badge_name);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO user_badges (user_id, badge_name, badge_icon) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $badge_name, $badge_icon);
        $stmt->execute();
    }
}

?>
