<table class="content-table" role="presentation" cellspacing="0" cellpadding="0" style="width:100%; font-family:Arial, sans-serif; background-color:#ffffff;">
    <tr>
        <td>
            <div class="email-content" style="max-width:600px; margin:auto; padding:20px;">
                <p style="font-size:15px;">Hello <?= $emailContent['user'] ?>,</p>

                <p style="font-size:15px;">This is a friendly reminder to follow up on a <?= $emailContent['module'] ?> assigned to you in <strong style="color:#4c9c2e;">Zamil CRM</strong>.</p>

                <p style="font-size:15px;">
                    <strong><?= ucwords($emailContent['module']) ?> Name:</strong> <?= $emailContent['lead_name'] ?><br>
                    <strong>Company:</strong> <?= $emailContent['company'] ?><br>
                    <strong>Contact:</strong> <?= $emailContent['email'] ?> / <?= $emailContent['phone'] ?><br>
                    <strong>Assigned On:</strong> <?= $emailContent['assigned_on'] ?><br>
                    <strong>Follow-Up Due:</strong> <?= $emailContent['follow_up_due'] ?>
                </p>

                <p style="font-size:15px;">Please make sure to follow up promptly to ensure continued engagement and customer satisfaction.</p>

                <p style="font-size:15px;">If you've already followed up, feel free to update the <?= $emailContent['module'] ?> status in the system.</p>

                <p style="font-size:15px;">Best regards,<br>The Zamil Plastic CRM Team</p>
            </div>
        </td>
    </tr>
</table>