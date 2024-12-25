<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $notificationID = $_POST['notificationID'];
  $feedbackScore = $_POST['feedbackScore'];

  $sql = "CALL ProcessFeedbackFromNotifications((SELECT CustomerID FROM Notifications WHERE NotificationID = ?), (SELECT DATE(NotificationDate) FROM Notifications WHERE NotificationID = ?), ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iii", $notificationID, $notificationID, $feedbackScore);
  if ($stmt->execute()) {
    echo "Feedback processed successfully.";
  } else {
    echo "Error processing feedback.";
  }
}
?>
