<table class="content-table" role="presentation" cellspacing="0" cellpadding="0" style="width:100%; font-family:Arial, sans-serif; background-color:#ffffff;">
    <tr>
        <td>
            <div class="email-content" style="max-width:600px; margin:auto; padding:20px;">
                <p style="font-size:15px;">Hello <?= $dealDetails['user'] ?>,</p>

                <p style="font-size:15px;">A new deal has been assigned to you in <strong style="color:#4c9c2e;">Zamil CRM</strong>.</p>

                <p style="font-size:15px;">
                    <strong>Deal Title:</strong> <?= $dealDetails['deal_name'] ?><br>
                    <strong>Deal Stage:</strong> <?= $dealDetails['deal_stage'] ?><br>
                    <strong>Deal Value:</strong> <?= $dealDetails['deal_value'] ?><br>
                    <strong>Status:</strong> <?= $dealDetails['deal_status'] ?><br>
                    <strong>Assigned On:</strong> <?= $dealDetails['assigned_on'] ?>
                </p>

                <p style="font-size:15px;">Please review the deal details and take the necessary actions to move it forward in the pipeline:</p>

                <p>
                    <a href="<?= $dealDetails['link'] ?>" style="color:#4c9c2e; text-decoration:none; font-weight:bold;">View Deal</a>
                </p>

                <p style="font-size:15px;">Feel free to reach out if you require any support or clarification.</p>

                <p style="font-size:15px;">All the best,<br>The Zamil Plastic CRM Team</p>
            </div>
        </td>
    </tr>
</table>