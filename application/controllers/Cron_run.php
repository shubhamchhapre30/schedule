<?php
    $ch = curl_init();

    // set URL for cronjob run
    curl_setopt($ch, CURLOPT_URL, base_url().'/cron/send_mail_notification');
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // grab URL and pass it to the browser
    curl_exec($ch);

    // close cURL resource, and free up system resources
    curl_close($ch);
?>

