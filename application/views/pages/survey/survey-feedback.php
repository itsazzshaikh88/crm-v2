<?php
function show_field($array, $key, $default = '')
{
	$value = $default;
	if (is_array($array)) {
		if (isset($array[$key]))
			$value = $array[$key];
	}
	return $value;
}

function show_rating($feedback_rating, $seq, $current)
{
	foreach ($feedback_rating as $rating) {
		if ($rating['seq'] == $seq) {
			return showRatingEmoji($rating['rating'], $current);
		}
	}
	return null;
}

function showRatingEmoji($selected, $current)
{
	$ratings = [
		array('icon' => 'bi bi-emoji-angry', 'value' => 'strongly-disagree'),
		array('icon' => 'bi bi-emoji-frown', 'value' => 'disagree'),
		array('icon' => 'bi bi-emoji-neutral', 'value' => 'neutral'),
		array('icon' => 'bi bi-emoji-smile', 'value' => 'agree'),
		array('icon' => 'bi bi-emoji-heart-eyes', 'value' => 'strongly-agree')
	];
	if ($current == $selected) {
		foreach ($ratings as $icon) {
			if ($icon['value'] == $selected) {
				return "<span><i class='$icon[icon]'></i></span>";
			}
		}
	}

	return null;
}

$user_type = $loggedInUser['userrole'] ?? 'Guest';
$is_admin = $user_type == 'admin' ? true : false;
$is_client = $user_type == 'client' ? true : false;

$header = isset($feedback['header']) ? $feedback['header'] : [];
$line = isset($feedback['line']) ? $feedback['line'] : [];
$survey = isset($feedback['survey']) ? $feedback['survey'] : [];

$client_feedback_rating = [];
if (isset($line['OPTIONS']))
	$client_feedback_rating = json_decode($line['OPTIONS'], TRUE);

?>
<style>
	/* 
		Strongly Disagree: #b42c2d
		Disagree: #d07835 
		Neutral: #6fbebc
		Agree: #63a25f
		Strongly Agree: #068356
	*/
	.survey-text {
		color: #0c53a7 !important;
	}

	.head-row i {
		font-size: 28px;
	}

	.survey-title-bg {
		background-color: #012939 !important;
	}

	.survey-table-azz tbody tr td {
		border-bottom: 1px solid #01293920 !important;
	}

	.survey-question-title {
		font-size: 14px !important;

	}

	.survey-question-text {
		font-size: 13px;
	}

	/* Apply to the table to ensure it takes full width */
	.table.survey-table-azz {
		width: 100%;
		table-layout: fixed;
		/* Ensure the table adjusts columns width automatically */
	}

	/* Set the width of the first cell */
	.table.survey-table-azz td:first-child {
		width: 50%;
	}

	/* Set the width for the remaining cells */
	.table.survey-table-azz td:not(:first-child) {
		width: 10%;
	}

	/* Optional: Adjust column width for better appearance */
	.table.survey-table-azz td,
	.table.survey-table-azz th {
		padding: 0.75rem;
		/* Add padding for better spacing */
		white-space: normal;
		/* Allows content to wrap */
		overflow-wrap: break-word;
		/* Ensures long words break correctly */
	}

	.table-survey-details tr td {
		padding: 0px !important;
		font-weight: 500 !important;
		padding-left: 5px !important;
		padding-right: 5px !important;
	}

	.text-icon {
		font-size: 32px !important;
	}
	.vertical-middle {
		vertical-align: middle;
	}
</style>
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxxl mx-14">
	<div class="content flex-row-fluid" id="kt_content">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-bg-color">
					<!-- <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title card-table-title p-0 sys-main-text font-weight-bold">Customer Survey Feedback: </h5>
                    </div> -->
					<div class="card-body">
						<div class="row">
							<div class="col-md-12 text-center">
								<div class="rounded border border-success bg-light-success border-dashed px-6 py-5 my-4 fw-bold">
									<p class="link-success fw-bold fs-6">Thank you very much for taking the time to complete our customer survey.</p>
									<p class="text-center mb-0 fw-normal">Dammam Second (2nd) Industrial City, 23rd Street, P.O. Box 1748, Al Khobar 31952, Saudi Arabia <br>
										Tel: (+966) 92 000 3679 Fax. (+966) 13 812 1477 <br>
										<a href="https://www.zamilplastic.com" class="text-success">Zamil Plastic Industries Company</a>
								</div>
							</div>

						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="table-container rounded border border-secondary bg-light-secondary border-dashed">
									<table class="table table-sm survey-table-azz" id="survey-table-azz">
										<tbody id="survey-body">

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>