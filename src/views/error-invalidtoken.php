<?php self::view('components/navbar.php', ['title' => 'Invalid Credentials']); ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="offset-sm-1 col-sm-11">
            <h3 class="mt-3">Invalid credentials.</h3> <br>
            Either your API token or API key is wrong.

            <ol>
                <li>Could you check you copied/pasted them correctly? (You can get or reset your credentials by logging in to your account on <a href="https://www.riddle.com" target="blank">riddle.com</a>.)</li>
                <ul class="riddle-ul">
                    <li>Want to connect to your personal account? Go to our ‘Account’  section > API > press 'Enable API'.</li>
                    <li>Need to connect to your team’s Riddles? Go to ‘Teams’ > edit your team > press 'Enable API'.</li>
                </ul>
            </ol>
            <ol start="2">
                <li>Copy the API token & key and insert them on the "Connect Account" page.</li>
            </ol>
            <a href="<?php echo \get_site_url(null, 'wp-admin/admin.php?page=riddle-admin-menu&disconnect=1') ?>" class="btn btn-riddle btn-cyan">Edit your credentials</a>
        </div>
    </div>
</div>
