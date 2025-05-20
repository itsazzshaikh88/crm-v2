<table class="content-table" role="presentation" cellspacing="0" cellpadding="0" style="width:100%; font-family:Arial, sans-serif; background-color:#ffffff;">
    <tr>
        <td>
            <div class="email-content" style="max-width:600px; margin:auto; padding:20px;">
                <p style="font-size:15px;">Hello <?= $leadDetails['user'] ?>,</p>

                <p style="font-size:15px;">You've been assigned a new lead in <strong style="color:#4c9c2e;">Zamil CRM</strong>. This is your opportunity to make the first impression count.</p>

                <p style="font-size:15px;">
                    <strong>Lead Name:</strong> <?= $leadDetails['lead_name'] ?><br>
                    <strong>Company:</strong> <?= $leadDetails['company'] ?><br>
                    <strong>Contact:</strong> <?= $leadDetails['email'] ?> / <?= $leadDetails['phone'] ?><br>
                    <strong>Assigned On:</strong> <?= $leadDetails['assigned_on'] ?>
                </p>

                <p style="font-size:15px;">Please review the lead information and initiate engagement as soon as possible:</p>

                <p>
                    <a href="<?= $leadDetails['link'] ?>" style="color:#4c9c2e; text-decoration:none; font-weight:bold;">View Lead</a>
                </p>

                <p style="font-size:15px;">If you need help or additional context, feel free to reach out to your team lead.</p>

                <p style="font-size:15px;">Best regards,<br>The Zamil Plastic CRM Team</p>
            </div>
        </td>
    </tr>
</table>