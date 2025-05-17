<style>
    /* .products_tech {
    background-color: rgb(211 208 208 / 77%);
    padding: 5px;
    font-weight: 200;
} */

    .radio_btn {
        width: 10%;
        padding: 15px;

    }

    .tag_title {
        width: 50%;
        padding: 5px;
    }


    .policy {
        border: 1px solid;
        padding: 5px;
        margin-top: 40px;
    }

    .survey-title-bg {
        background-color: #012939;
    }

    .table.survey-table-azz td,
    .table.survey-table-azz th {
        padding: 0.75rem;
        /* Add padding for better spacing */
        white-space: normal;
        /* Allows content to wrap */
        overflow-wrap: break-word;
        /* Ensures long words break correctly */
    }

    .vertical-middle {
        vertical-align: middle !important;
    }
</style>

<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxxl mx-14">
    <div class="content flex-row-fluid" id="kt_content">
        <form id="form" class="form d-flex flex-column " method="POST" enctype="multipart/form-data" onsubmit="submitForm(event)">
            <input type="hidden" name="SURVEY_ID" id="SURVEY_ID" value="<?= $SURVEY_ID ?>">
            <input type="hidden" name="CLIENT_ID" id="CLIENT_ID" value="<?= $currentLoggedInuser['userid'] ?? 0 ?>">


            <!--begin::PAGE CONTENT GOES FROM HERE-->
            <div class="card">
                <div class="row mt-2">
                    <div class="col-md-12 ">
                        <div class="card">
                            <div class="card-header py-2">
                                <h2 class="my-4 fw-normal text-primary">Survey : <span class="text-gray-900" id="PLACEHOLDER_SURVEY_NAME">

                                        <div class="skeleton-box" style="width: 480px; height: 30px;"></div>
                                    </span></h2>
                                <div class="rounded border border-warning bg-light-warning border-dashed px-6 py-5 my-4">
                                    <p class="link-warning fw-bold fs-6">Privacy Policy:</p>
                                    <span class="text-gray-600 fw-semibold fs-6">In accordance with our policies on safeguarding personal
                                        information. Zamil Plastic assures that your responses to this survey are safe
                                        and won't be linked with any identifying information or shared with any third
                                        parties.</span>
                                </div>
                            </div>
                            <div class="card-body mb-0 mt-0">
                                <div class="table-responsive table_div border border-secondary bg-light-secondary border-dashed bg-slate-50">
                                    <table class="table table-sm survey-table-azz">
                                        <tbody>
                                            <tr class="bg-white head-row">
                                                <td class="vertical-middle" style="width: 50%;">
                                                    <h6 class="font-weight-normal mb-0">Customer Evaluation</h6>
                                                    <p class="survey-text mb-0" style="color: #0c53a7;"> Please tick the
                                                        box that best indicates
                                                        your degree of satisfaction.</p>
                                                </td>
                                                <?php
                                                $ratings = survey_ratings();
                                                foreach ($ratings as $rating):
                                                ?>
                                                    <td class="text-center bg-light-<?= $rating['color'] ?> vertical-middle"
                                                        style=" width:10%;">
                                                        <h6 class="mb-2 d-flex justify-content-center  align-items-center "
                                                            style="font-size: 24px;">
                                                            <?= $rating['icon'] ?>
                                                        </h6>
                                                        <?= $rating['title'] ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                            <!-- Get and Display survey questions  -->
                                            <?php
                                            $questions = survey_questions();
                                            $counter = 1;
                                            $ratings_criteria = [array('value' => 'strongly-disagree', 'color' => 'danger'), array('value' => 'disagree', 'color' => 'warning'), array('value' => 'neutral', 'color' => 'secondary'), array('value' => 'agree', 'color' => 'info'), array('value' => 'strongly-agree', 'color' => 'success')];
                                            foreach ($questions as $question):
                                            ?>
                                                <tr class="survey-title-bg">
                                                    <td colspan="6" class="text-white mr-2"><?= $question['title'] ?></td>
                                                </tr>
                                                <?php
                                                $sub_questions = $question['questions'];
                                                $inner = 0;
                                                foreach ($sub_questions as $que):
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <span class="mb-0 survey-text survey-question-title">
                                                                <?= ++$inner ?>. <?= $que['title'] ?>
                                                            </span>
                                                            <p class="mb-0 survey-question-text" style="color: #0c53a7;">
                                                                <?= $que['questions'] ?></p>
                                                        </td>
                                                        <?php foreach ($ratings_criteria as $criteria): ?>
                                                            <td
                                                                class="text-center vertical-middle  bg-light-<?= $criteria['color'] ?>">
                                                                <input required type="radio" class=""
                                                                    name="survey-line-<?= $counter ?>"
                                                                    id="survey-line-<?= $counter ?>"
                                                                    value="<?= $criteria['value'] ?>">
                                                            </td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php $counter++;
                                                endforeach;
                                                ?>
                                            <?php
                                            endforeach; ?>
                                            <tr class="survey-title-bg">
                                                <td colspan="6" class="text-white">F - Further Comments</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" rowspan="2" class="vertical-middle">
                                                    <h6 class="mb-0 survey-text survey-question-title fw-normal">
                                                        Would you recommend/have you ever recommended Zamil Plastic to
                                                        any other company?
                                                    </h6>
                                                </td>
                                                <td class="text-center">
                                                    <h6 class="fw-normal">Yes</h6>
                                                </td>
                                                <td class="text-center">
                                                    <h6 class="fw-normal">No</h6>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-center">
                                                    <input required="" type="radio" class="" name="RECOMMENDATION"
                                                        id="RECOMMENDATION" value="yes">
                                                </td>
                                                <td class="text-center">
                                                    <input required="" type="radio" class="" name="RECOMMENDATION"
                                                        id="RECOMMENDATION" value="no">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <h6 class="mb-0 survey-text survey-question-title fw-normal">
                                                        Do you have any comments or suggestion that would help us
                                                        improve our quality of customer service?
                                                    </h6>
                                                    <textarea name="COMMENTS" id="COMMENTS"
                                                        class="mt-4 form-control form-control-sm border border-blue-200 fw-normal" rows="5"
                                                        placeholder="Share your valuable feedback and suggestions to help us enhance our products and services for you!"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end">
                                                    <button id="survey-submit-btn" type="submit" class="btn btn-success">
                                                        <i class="fa-solid fa-plus"></i> Submit Survey
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!--end::PAGE CONTENT GOES FROM HERE-->
    </div>
</div>
<?php
$this->load->view('loaders/full-page-loader');
?>