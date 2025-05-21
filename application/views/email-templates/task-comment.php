<table class="content-table" role="presentation" cellspacing="0" cellpadding="0" style="width:100%; font-family:Arial, sans-serif; background-color:#ffffff;">
    <tr>
        <td>
            <div class="email-content" style="max-width:600px; margin:auto; padding:20px;">
                <p style="font-size:15px;">Hello <?= $commentDetails['user'] ?>,</p>

                <p style="font-size:15px;">A new comment has been added to the task <strong style="color:#4c9c2e;">“<?= $commentDetails['task_name'] ?>”</strong> in <strong style="color:#4c9c2e;">Zamil CRM</strong>.</p>

                <p style="font-size:15px;"><strong>Commented By:</strong> <?= $commentDetails['commented_by'] ?></p>

                <blockquote style="margin:15px 0; padding:10px 15px; background:#f7f7f7; border-left:4px solid #4c9c2e; font-size:14px; color:#333;">
                    <?= $commentDetails['comment_text'] ?>
                </blockquote>

                <p style="font-size:15px;">To view the full discussion or reply, please click the link below:</p>

                <p>
                    <a href="<?= $commentDetails['link'] ?>" style="color:#4c9c2e; text-decoration:none; font-weight:bold;">View Task Comments</a>
                </p>

                <p style="font-size:15px;">Stay aligned and keep your task progress updated.</p>

                <p style="font-size:15px;">Regards,<br>The Zamil Plastic CRM Team</p>
            </div>
        </td>
    </tr>
</table>