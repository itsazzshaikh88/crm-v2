<div class="row mt-5">
    <div class="col-md-12 mt-5">
        <div class="border border-secondary p-5 rounded">
            <h5 class="card-title">Session Management</h5>
            <form id="session-form" onsubmit="submitUserSessionSetting(event)" method="post" enctype="multipart/form-data">
                <input type="hidden" name="ID" id="ID" value="">
                <div class="form-group row mb-0">
                    <label for="SESSION_TIMEOUT_MINUTES" class="col-md-4 col-form-label text-muted">Session Timeout (Minutes) <span class="float-end">:</span></label>
                    <div class="col-md-3">
                        <input type="number" name="SESSION_TIMEOUT_MINUTES" id="SESSION_TIMEOUT_MINUTES" class="form-control form-control-sm" min="1">
                        <span class="text-danger err-lbl mb-0" id="lbl-session-SESSION_TIMEOUT_MINUTES"></span>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label for="REMEMBER_ME_DAYS" class="col-md-4 col-form-label text-muted">Remember Me (Days) <span class="float-end">:</span></label>
                    <div class="col-md-3">
                        <input type="number" name="REMEMBER_ME_DAYS" id="REMEMBER_ME_DAYS" class="form-control form-control-sm" min="0">
                        <span class="text-danger err-lbl mb-0" id="lbl-session-REMEMBER_ME_DAYS"></span>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label for="TIMEZONE" class="col-md-4 col-form-label text-muted">Timezone <span class="float-end">:</span></label>
                    <div class="col-md-3">
                        <input type="text" name="TIMEZONE" id="TIMEZONE" class="form-control form-control-sm" value="UTC">
                        <span class="text-danger err-lbl mb-0" id="lbl-session-TIMEZONE"></span>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label for="PREFERRED_LANGUAGE" class="col-md-4 col-form-label text-muted">Preferred Language <span class="float-end">:</span></label>
                    <div class="col-md-3">
                        <input type="text" name="PREFERRED_LANGUAGE" id="PREFERRED_LANGUAGE" class="form-control form-control-sm" value="en">
                        <span class="text-danger err-lbl mb-0" id="lbl-session-PREFERRED_LANGUAGE"></span>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label for="UI_THEME" class="col-md-4 col-form-label text-muted">UI Theme <span class="float-end">:</span></label>
                    <div class="col-md-6">
                        <select name="UI_THEME" id="UI_THEME" class="form-control form-control-sm">
                            <option value="system">System Default</option>
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                        </select>
                        <span class="text-danger err-lbl mb-0" id="lbl-session-UI_THEME"></span>
                    </div>
                </div>
                <div class="form-group row text-end mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" id="session-submit-button" class="btn btn-secondary">Save Settings</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12 mt-5">
        <div class="border border-secondary p-5 rounded">
            <h5 class="card-title">Security Question</h5>
            <form id="security-question-form" onsubmit="submitUserSecurityQuestionSetting(event)" method="post" enctype="multipart/form-data">
                <input type="hidden" name="SECURITY_ID" id="SECURITY_ID" value="">
                <div class="form-group row mb-0">
                    <label for="QUESTION" class="col-md-4 col-form-label text-muted">Security Question <span class="float-end">:</span></label>
                    <div class="col-md-8">
                        <select name="QUESTION" id="QUESTION" class="form-control form-control-sm">
                            <option value="" disabled selected>Select a Security Question</option>
                            <option value="1">What was your childhood nickname?</option>
                            <option value="2">What is the name of your first pet?</option>
                            <option value="3">What was the name of your first school?</option>
                            <option value="4">In what city were you born?</option>
                            <option value="5">What is your mother’s maiden name?</option>
                            <option value="6">What was the make of your first car?</option>
                            <option value="7">What was your favorite teacher's name?</option>
                            <option value="8">What is your favorite food?</option>
                            <option value="9">What is the title of your favorite book?</option>
                            <option value="10">What was your dream job as a child?</option>
                        </select>

                        <span class="text-danger err-lbl mb-0" id="lbl-question-QUESTION"></span>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label for="ANSWER" class="col-md-4 col-form-label text-muted">Answer <span class="float-end">:</span></label>
                    <div class="col-md-8">
                        <input type="text" name="ANSWER" id="ANSWER" class="form-control form-control-sm" min="0" placeholder="Enter your security question answer">
                        <span class="text-danger err-lbl mb-0" id="lbl-question-ANSWER"></span>
                    </div>
                </div>

                <div class="form-group row text-end mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" id="question-submit-button" class="btn btn-secondary">Save Settings</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>