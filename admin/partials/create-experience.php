<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-light">
                    <form id="nexweave-experience-form">
                        <div class="form-group">
                            <label for="experience_name">Experience Name </label>
                            <input type="text" class="form-control" id="experience_name" aria-describedby="experience_name" placeholder="Name" name="experience_name">
                        </div>

                        <div class="form-group">
                            <label for="selectEnvironment">Select Environment</label>
                            <select name="environment" class="form-control" id="environment">
                                <option value="production">Production</option>
                                <option value="beta">Beta</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Enter Default Experience short ID</label>
                            <input type="text" required class="form-control" id="experience_id" aria-describedby="emailHelp" placeholder="Enter experience ID" name="shortid">
                        </div>

                        <div class="form-group">
                            <label for="campaignId">Enter Campaign ID</label>
                            <input type="text" class="form-control" id="campaignid" aria-describedby="emailHelp" placeholder="Enter Campaign ID" name="campaignid">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">
                                Enter variables override for your template
                                <small id="variableHelp" class="form-text text-muted mb-2">
                                    Let's say you want to replace a variable named <b>USERNAME</b> from your template with the wordpress user's attribute pass.
                                    <code>USERNAME:[%WP_VAR%]</code>
                                    <br>
                                    you can get <code>%WP_VAR%</code> from the table on the left
                                </small>
                            </label>
                            <textarea name="variables" id="variables_area" class="form-control" col="30"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" class="form-control" value="1" name="isFormVisible" id="isFormVisible"> Check to render form with video experinece
                        </div>
                        <div id="apiKey-wrapper" style="display: none;">
                            <div class="form-group">
                                <label for="apiKey">Enter Your API Key</label>
                                <input type="text" placeholder="Enter your api key. eg. KY78-DT63-DHS7-DHA5" class="form-control" id="apiKey" value="" name="apiKey" />
                            </div>

                            <div class="form-group">
                                <label for="formTitle">Form Title</label>
                                <input type="text" placeholder="Form Title" class="form-control" id="formTitle" value="" name="formTitle" />
                            </div>

                            <div class="form-group">
                                <label for="buttonText">Button Text</label>
                                <input type="text" placeholder="Button text on the form" class="form-control" id="buttonText" value="" name="buttonText" />
                            </div>
                        </div>


                        <button type="submit" id="generate_shortcode" class="btn btn-primary btn-sm">Generate Short Code</button>

                        <button class="btn btn-primary btn-sm" id="loading_button" type="button" style="display: none;" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card bg-light">
                    <h6 class="card-title text-center">Your shortcode</h6>
                    <div class="input-group mb-3">
                        <input id="generatedshortcode" name="shortcode" type="text" class="form-control" placeholder="Short code" aria-label="Short Code" aria-describedby="basic-addon1">
                        <div class="input-group-append">
                            <button type="button" data-clipboard-target="#generatedshortcode" class="btn btn-primary btn-sm">
                                <i class="fa fa-files-o" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-light">
            <h5 class="card-title text-center">Available variables</h5>
            <?php
            $current_user = wp_get_current_user();
            $wp_username = $current_user->user_login;
            $wp_firstname = $current_user->user_firstname;
            $wp_lastname = $current_user->user_lastname;
            $wp_email = $current_user->user_email;
            ?>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item inline-flex">
                        <p>UserName</p>
                        <div class="row">
                            <div class="col-md-8">
                                <p><?php echo $wp_username ?></p>
                            </div>
                            <div class="col-md-4">
                                <button type="button" data-clipboard-text="[WP_USERNAME]" class="btn btn-primary btn-sm">
                                    <i class="fa fa-files-o" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <p>First Name</p>
                        <div class="row">
                            <div class="col-md-8">
                                <p><?php echo $wp_firstname ?></p>
                            </div>
                            <div class="col-md-4">
                                <button type="button" data-clipboard-text="[WP_FIRSTNAME]" class="btn btn-primary btn-sm">
                                    <i class="fa fa-files-o" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <p>Last Name</p>
                        <div class="row">
                            <div class="col-md-8">
                                <p><?php echo $wp_lastname ?></p>
                            </div>
                            <div class="col-md-4">
                                <button type="button" data-clipboard-text="[WP_LASTNAME]" class="btn btn-primary btn-sm">
                                    <i class="fa fa-files-o" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <p>Email</p>
                        <div class="row">
                            <div class="col-md-8">
                                <p><?php echo  $wp_email ?></p>
                            </div>
                            <div class="col-md-4">
                                <button type="button" data-clipboard-text="[WP_EMAIL]" class="btn btn-primary btn-sm">
                                    <i class="fa fa-files-o" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>