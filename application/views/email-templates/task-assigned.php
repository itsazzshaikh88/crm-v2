<table class="content-table" role="presentation" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <div class="email-content">
                <p>Hello <?= $task['user'] ?? '' ?>,</p>

                <p>You’ve been assigned a new task in <strong style="color:#4c9c2e;">Zamil CRM</strong>.</p>

                <p>
                    <strong>Task:</strong> <?= $task['title'] ?? '' ?><br>
                    <strong>Target Date:</strong> <?= $task['target_date'] ?? '' ?><br>
                    <strong>Assigned By:</strong> <?= $task['assigned_by'] ?? '' ?>
                </p>

                <p>Click below to view and begin working on your task:</p>
                <p>
                    <a href="<?= $task['link'] ?? 'javascript:void(0)' ?>" style="color:#4c9c2e; text-decoration:none; font-weight:bold;">View Task</a>
                </p>

                <p>Let us know if you need any support.</p>
                <p>Best regards,<br>The Zamil Plastic CRM Team</p>
            </div>
        </td>
    </tr>
</table>